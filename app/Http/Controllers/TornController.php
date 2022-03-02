<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Torn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Carbon\Carbon;
use \DateTime;
use App\Models\Jornada;
use App\Models\User;
use App\Models\Comanda;

class TornController extends Controller
{
    public function create(){
        $user = Auth::user();
        $tornTreb = Torn::where(['treballador' => Auth::id()])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        $dia = Jornada::where(['treballador' => Auth::id()])->orderBy('id','desc')->take(5)->get();
        return view('jornada', compact('user','tornTreb','dia'));
        
        /* jaseando */
        $id = 4;
        $torns = Torn::where(['treballador' => $id])->orderBy('id','desc')->take(10)->get();
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

        $torn = Torn::where(['treballador' => Auth::id()])->latest()->first();

        $activitat = new Torn();

            $activitat-> treballador = $user->id;
            $activitat-> jornada = now();
            $jornadaInici = now();
            $activitat-> iniciJornada = Carbon::parse($jornadaInici)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

            $activitat->save();
            $activitat = Torn::all();
             
            $novaJornada = Jornada::firstOrCreate(//busco el registre concret, i si no el troba, el creo
                ['dia'=> $diaFormat, 'treballador'=> Auth::id()]
            );

        $tornTreb = Torn::where(['treballador' => $user->id])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        $dia = Jornada::where(['treballador' => $user->id])->orderBy('id','desc')->take(5)->get();
        return view('jornada', compact('user','tornTreb','dia'));
        
    } 

    public function update(Request $request){
        $user = Auth::user();
        //comprovacio de si hi ha alguna tasca inacabada i per tant, no es pot acabar la jornada
        $checkTask = Comanda::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        if ($checkTask == null || $checkTask->total > 0){


            /* $activitat->update([
                'fiJornada' => $request->input('final-Jornada'),
            ]); */
            //$activitat = Torn::find(3);
            $jornada = now();//"2022-02-01T09:08:09.674363Z"
            $jorn = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d');//2022-02-01
            $finalFormat = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');//2022-02-03 09:20:21
            $activitat = Torn::where([/* 'jornada' => $jorn,  */'treballador' => $user->id])->latest()->first();
            $activitat-> fiJornada = $finalFormat;//guardo fiJornada a la BBDD
            //carbon = "2022-01-31T11:34:39.000000Z"

            $activitat-> update();
            $inici = Torn::where(['treballador' => $user->id])
            ->get('iniciJornada')->last();//{"iniciJornada":"2022-02-01 10:24:10"}
            $iniciFormat = $inici->iniciJornada;//2022-02-03 09:07:48
            $fi = Torn::where(['jornada' => $jorn, 'treballador' => $user->id])
            ->get('fiJornada')->last();//{"fiJornada":"2022-02-01 11:55:45"}

            $iniciSegs = strtotime($iniciFormat);//1643875668
            $finalSegs = strtotime($finalFormat);//1643877132
            
            $resta = $finalSegs - $iniciSegs; #resto la quantitat de segons que han passat des del inici del temps unix
            $min = $resta/60;
            $hores = $min/60;
            $activitat -> total = $min;
            $activitat-> update();


            /*consultes per a sumar les hores de diferents torns: de la taula ACTIVITATS a la de JORNADES*/
            $turnos = Torn::where(['jornada' => $jorn,'treballador' => Auth::id()])->orderBy('id','desc')->get('total');

            //suma de tots els torns de dia X
            $n = count($turnos);
            $suma = 0;
            for ($i = 0; $i < $n; $i++){
                $suma += $turnos[$i]->total;
            }

                //afegeixo el total al TOTAL de la jornada
                $totalJornada = $suma;
                $novaJornada = Jornada::where(['treballador' => $user->id])->latest()->first();
                $novaJornada-> treballador = $user->id;
                $novaJornada-> dia = now();
                $novaJornada -> total = $totalJornada;
                $novaJornada-> update();

        $tornTreb = Torn::where(['treballador' => $user->id])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        return view('jornada', compact('user','tornTreb'));    

        } else {
            return response()->json(false,200);//task unfinished
        }
        

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
        //torn sense acabar
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();

        if (!($tornComprovacio == null)){//si la jornada no esta acabada
            return response()->json(true, 200);
        } else {
            return response()->json(false, 200);
            
        }
    }
}
