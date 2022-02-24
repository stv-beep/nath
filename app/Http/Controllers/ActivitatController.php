<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Carbon\Carbon;
use \DateTime;
use App\Models\Jornada;
use App\Models\Torn;
use App\Models\User;

class ActivitatController extends Controller
{
    public function create(){
        $user = Auth::user();
        $tornTreb = Activitat::where(['treballador' => Auth::id()])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        $dia = Jornada::where(['treballador' => Auth::id()])->orderBy('id','desc')->take(5)->get();
        return view('jornada', compact('user','tornTreb','dia'));
        
        /* jaseando */
        $id = 4;
        $torns = Activitat::where(['treballador' => $id])->orderBy('id','desc')->take(10)->get();
        $users = User::findOrFail($id);
        return response()->json([
            'msg2' => 'USUARI:', 
            $users,
            'msg' => 'mostrant torns', 
            $torns,
        ]);
    }

    public function store(Request $request){
        $user = Auth::user();
        

        $d = now();
        $diaFormat = Carbon::parse($d)->setTimezone('Europe/Madrid')->format('Y-m-d');

        $torn = Activitat::where(['treballador' => Auth::id()])->latest()->first();

        $activitat = new Activitat();

            $activitat-> treballador = $user->id;
            $activitat-> jornada = now();
            $jornadaInici = now();
            $activitat-> iniciJornada = Carbon::parse($jornadaInici)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            //$activitat-> iniciJornada = $request->input("inici-jornada");

            $activitat->save();
            $activitat = Activitat::all();
            
             
            if ($user->torn == 1) {
                $novaJornada = new Jornada();
                $novaJornada->dia = $diaFormat;
                $novaJornada-> treballador = Auth::id();
                $novaJornada->save();
            } else {
                $novaJornada = Jornada::firstOrCreate(//busco el registre concret, i si no el troba, el creo
                    ['dia'=> $diaFormat, 'treballador'=> Auth::id()]
                );
            }
        $tornTreb = Activitat::where(['treballador' => $user->id])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        $dia = Jornada::where(['treballador' => $user->id])->orderBy('id','desc')->take(5)->get();
        return view('jornada', compact('user','tornTreb','dia'));
    } 

    public function update(Request $request){
        //https://www.ironwoods.es/blog/laravel/eloquent-consultas-frecuentes
        $user = Auth::user();

            /* $activitat->update([
                'fiJornada' => $request->input('final-Jornada'),
            ]); */
        //$activitat = Activitat::find(3);
        $jornada = now();//"2022-02-01T09:08:09.674363Z"
        $jorn = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d');//2022-02-01
        $finalFormat = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');//2022-02-03 09:20:21
        $activitat = Activitat::where([/* 'jornada' => $jorn,  */'treballador' => $user->id])->latest()->first();
        $activitat-> fiJornada = $finalFormat;//guardo fiJornada a la BBDD
        //carbon = "2022-01-31T11:34:39.000000Z"

        $activitat-> update();
        $inici = Activitat::where(['treballador' => $user->id])
        ->get('iniciJornada')->last();//{"iniciJornada":"2022-02-01 10:24:10"}
        $iniciFormat = $inici->iniciJornada;//2022-02-03 09:07:48
        $fi = Activitat::where(['jornada' => $jorn, 'treballador' => $user->id])
        ->get('fiJornada')->last();//{"fiJornada":"2022-02-01 11:55:45"}

        $iniciSegs = strtotime($iniciFormat);//1643875668
        $finalSegs = strtotime($finalFormat);//1643877132
        
        $resta = $finalSegs - $iniciSegs; #resto la quantitat de segons que han passat des del inici del temps unix
        $min = $resta/60;
        $hores = $min/60;
        $activitat -> total = $min;
        $activitat-> update();


        
        /*consultes per a sumar les hores de diferents torns: de la taula ACTIVITATS a la de JORNADES*/
        if ($user->torn == 2){//jornada partida
        //tenim hores guardades 
        $activitatID = Activitat::where([/* 'jornada' => $jorn, */'treballador' => $user->id])->orderBy('id','desc')->take(2)->get();
        //$mida = sizeof($activitatID);
        $turnos = Activitat::where([/* 'jornada' => $jorn, */'treballador' => $user->id])->orderBy('id','desc')->take(2)->get('total');
        


        //primera meitat
        $h1 = $turnos->first();
        $h1 = $h1->total;
        

        $h2 = $turnos->last();
        $h2 = $h2->total;


        //si hi ha dos hores que venen del mateix ID d'activitat, no les tindra que sumar
        //o millor dit, si nomes troba una activitat, i per tant nomes un ID, no lo tindra que sumar dos vegades
            if (sizeof($activitatID)<2){

                $totalJornada = $h1;
                $novaJornada = Jornada::where([/* 'dia' => $jorn,  */'treballador' => $user->id])->latest()->first();
                $novaJornada-> treballador = $user->id;
                $novaJornada-> dia = now();
                $novaJornada -> total = $totalJornada;
                $novaJornada-> update();
                

            } else {

            

            $totalJornada = $h1 + $h2;

            $novaJornada = Jornada::where([/* 'dia' => $jorn, */ 'treballador' => $user->id])->latest()->first();
            
            $novaJornada-> treballador = $user->id;
            $novaJornada-> dia = now();

            $novaJornada -> total = $totalJornada;

            $novaJornada-> update();

        
            
            }
        } else {//jornada intensiva
            $intensiva = Activitat::where(['treballador' => $user->id])->latest()->first();
            $intensiva -> total = $min;
            $intensiva-> update();
            $JornadaTotal = Jornada::where(['treballador' => $user->id])->latest()->first();
            $JornadaTotal->total = $min;
            $JornadaTotal->update();
        }

        $tornTreb = Activitat::where(['treballador' => $user->id])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        return view('jornada', compact('user','tornTreb'));          


        /* PROVES */

        //$fi2 = $fi->fiJornada;
/* 
        $iniciString = substr($inici,17,19);//2022-02-01 12:45:18
        $ik = strtotime($iniciString); */
       // $inici2 = date('Y-m-d H:i:s', $ik);//2022-02-01 12:45:18
/* 
        $fiString = substr($fi,14,19);//2022-02-01 12:45:18
        $fj = strtotime($fiString);
        $fi2 = date('Y-m-d H:i:s', $fj);//2022-02-01 12:45:18 */

       /*  $i = strtotime(substr($inici,17,19));#1643715918
        $f = strtotime(substr($fi,17,19));#{"fiJornada":"2022-02-01 11:55:45"}
        $f0 = strtotime(substr($final,0));#1643789914 aixo son segons desde inici unix */
     /* $resta = $inici2 - $final; #resto la quantitat de segons que han passat des del inici del temps unix
        $min = $resta/60;
        $hores = $min/60; */
        /* $inici2 = date('Y-m-d H:i:s', $i);
        $fi2 = date('Y-m-d H:i:s', $f0); */
        /* $inicidata = new \DateTime($i);
        $inicidata->format('Y-m-d H:i:s'); */
        //$interval = $inici2->diffInMinutes($fi2);      
       
    }

    public function checkTorn(Request $request){
        $user = Auth::user();
        $tornComprovacio = Activitat::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();

        if (!($tornComprovacio == null)){
            return response()->json(true, 200);
        } else {
            return response()->json(false, 200);
            
        }
    }
}
