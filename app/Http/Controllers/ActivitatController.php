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
use App\Models\User;

class ActivitatController extends Controller
{
    public function create(){
        $user = Auth::user();
        return view('activitat-form',compact('user'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $activitat = new Activitat();

        $d = now();
        $diaFormat = Carbon::parse($d)->setTimezone('Europe/Madrid')->format('Y-m-d');

        $novaJornada = Jornada::firstOrCreate(//busco el registre concret, i si no el troba, el creo
            ['dia'=> $diaFormat, 'treballador'=> Auth::id() ]
            /* ['dia'=> $diaFormat ], */
            
        );
        
        
        
        $activitat-> treballador = $user->id;
        $activitat-> jornada = now();
        $jornadaInici = now();
        $activitat-> iniciJornada = Carbon::parse($jornadaInici)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        //$activitat-> iniciJornada = $request->input("inici-jornada");

        $activitat->save();
        $activitat = Activitat::all();
        
        return view('activitat-form',compact('user'));
        //return $request->all();
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
        $inici = Activitat::where([/* 'jornada' => $jorn,  */'treballador' => $user->id])
        ->get('iniciJornada')->last();//{"iniciJornada":"2022-02-01 10:24:10"}
        $iniciFormat = $inici->iniciJornada;//2022-02-03 09:07:48
        $fi = Activitat::where(['jornada' => $jorn, 'treballador' => $user->id])
        ->get('fiJornada')->last();//{"fiJornada":"2022-02-01 11:55:45"}

        $iniciSegs = strtotime($iniciFormat);//1643875668
        $finalSegs = strtotime($finalFormat);//1643877132
        
        $resta = $finalSegs - $iniciSegs; #resto la quantitat de segons que han passat des del inici del temps unix
        $min = $resta/60;
        $hores = $min/60;
        $activitat -> total = $hores;
        $activitat-> update();


        /*consultes per a sumar les hores de diferents torns: de la taula ACTIVITATS a la de JORNADES*/
        
        //tenim hores guardades 
        $partides = Activitat::where(['jornada' => $jorn,'treballador' => $user->id])->orderBy('id','desc')->take(2)->get('total');
        
        //primera meitat
        $h1 = $partides->first();
        $h1 = $h1->total;
        

        $h2 = $partides->last();
        $h2 = $h2->total;

        $totalJornada = $h1 + $h2;

        $novaJornada = Jornada::where(['dia' => $jorn, 'treballador' => $user->id])->latest()->first();
        
        $novaJornada-> treballador = $user->id;
        $novaJornada-> dia = now();

        $novaJornada -> total = $totalJornada;

        $novaJornada-> update();
        





        /*  return $totalJornada; */
        return view('activitat-form',compact('user'));    


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
}
