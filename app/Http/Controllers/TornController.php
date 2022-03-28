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
        
    }

    public function store(Request $request){
        $user = Auth::user();
       
        $d = now();
        $diaFormat = Carbon::parse($d)->setTimezone('Europe/Madrid')->format('Y-m-d');

        $torn = Torn::where(['treballador' => Auth::id()])->latest()->first();

        $shift = new Torn();

            $shift-> treballador = $user->id;
            $shift-> jornada = now();
            $workStart = now();
            $shift-> iniciTorn = Carbon::parse($workStart)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $shift-> info = $request->info;
            $shift-> geolocation = $request->x;
            $shift->save();
            $shift = Torn::all();
             
            $novaJornada = Jornada::firstOrCreate(//busco el registre concret, i si no el troba, el creo
                ['dia'=> $diaFormat, 'treballador'=> Auth::id()],
                ['geolocation'=> $request->x, 'info'=> $request->info]
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


            $jornada = now();//"2022-02-01T09:08:09.674363Z"
            $jorn = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d');//2022-02-01
            $finalFormat = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');//2022-02-03 09:20:21
            $shift = Torn::where([/* 'jornada' => $jorn,  */'treballador' => $user->id])->latest()->first();
            $shift-> fiTorn = $finalFormat;//guardo fiTorn a la BBDD
            //carbon = "2022-01-31T11:34:39.000000Z"
            $shift-> update();
            $inici = Torn::where(['treballador' => $user->id])
            ->get('iniciTorn')->last();//{"iniciTorn":"2022-02-01 10:24:10"}
            $iniciFormat = $inici->iniciTorn;//2022-02-03 09:07:48
            $fi = Torn::where(['jornada' => $jorn, 'treballador' => $user->id])
            ->get('fiTorn')->last();//{"fiTorn":"2022-02-01 11:55:45"}

            $iniciSegs = strtotime($iniciFormat);//1643875668
            $finalSegs = strtotime($finalFormat);//1643877132
            
            $resta = $finalSegs - $iniciSegs; #resto la quantitat de segons que han passat des del inici del temps unix
            $min = $resta/60;
            $hores = $min/60;
            $shift -> total = $hores;
            $shift-> update();


            /*consultes per a sumar les hores de diferents torns: de la taula TORNS a la de JORNADES*/
            $finalShift = Torn::where(['treballador' => $user->id])->get('fiTorn')->last();
            $finalString = substr($finalShift,19,2);//dia, ex: 2022-03-(24) ...
            //select torns amb fi el mateix dia, select 2 caracters a partir del 10 començant per detras de fiTorn = 2022-03-""23"" 10:24:10
            $turnos = DB::select("SELECT total FROM `torns` WHERE SUBSTRING(fiTorn, CHAR_LENGTH(fiTorn)-10,2) = $finalString AND `treballador` = $user->id");
            //$turnos = Torn::where(['jornada' => $jorn,'treballador' => Auth::id()])->orderBy('id','desc')->get('total');

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
                $novaJornada -> total = $totalJornada;// /60
                $novaJornada-> update();

        $tornTreb = Torn::where(['treballador' => $user->id])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        return view('jornada', compact('user','tornTreb'));    

        } else {
            return response()->json(false,200);//task unfinished
        }   
       
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
