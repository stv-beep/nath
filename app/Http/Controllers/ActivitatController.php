<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Carbon\Carbon;
use \DateTime;

class ActivitatController extends Controller
{
    public function create(){
        $user = Auth::user();
        return view('activitat-form',compact('user'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $activitat = new Activitat();
        /* $activitat-> treballador = $request->input("treballador"); */
        $activitat-> treballador = $user->id;
        $activitat-> jornada = now();
        $activitat-> iniciJornada = $request->input("inici-jornada");

        $activitat->save();
        $activitat = Activitat::all();
        
        return view('home',compact('user'));
        //return $request->all();
} 

    public function update(Request $request){
        //https://www.ironwoods.es/blog/laravel/eloquent-consultas-frecuentes
        $user = Auth::user();

            /* $activitat->update([
                'fiJornada' => $request->input('final-Jornada'),
            ]); */
        //$activitat = Activitat::find(3);
        //$activitat = Activitat::where(['jornada' => '2022-01-31', 'treballador' => '1'])->latest()->first();
        $jornada = now();//"2022-02-01T09:08:09.674363Z"
        $jorn = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d');//2022-02-01
        $final = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');//"2022-02-01T10:22:13.000000Z"
        $activitat = Activitat::where(['jornada' => $jorn, 'treballador' => $user->id])->latest()->first();

        //carbon = "2022-01-31T11:34:39.000000Z"
        $activitat-> fiJornada = $request->input("final-jornada");
        $fijorn = new Carbon($request->input("final-jornada"));

        $activitat-> update();
        $inici = Activitat::where(['jornada' => $jorn, 'treballador' => $user->id])
        ->get('iniciJornada')->last();//{"iniciJornada":"2022-02-01 10:24:10"}
        $fi = Activitat::where(['jornada' => $jorn, 'treballador' => $user->id])
        ->get('fiJornada')->last();//{"fiJornada":"2022-02-01 11:55:45"}



        $iniciString = substr($inici,17,19);//2022-02-01 12:45:18
        $ik = strtotime($iniciString);
        $inici2 = date('Y-m-d H:i:s', $ik);//2022-02-01 12:45:18


        $fiString = substr($fi,14,19);//2022-02-01 12:45:18
        $fj = strtotime($fiString);
        $fi2 = date('Y-m-d H:i:s', $fj);//2022-02-01 12:45:18


        $i = strtotime(substr($inici,17,19));#1643715918
        $f = strtotime(substr($fi,17,19));#{"fiJornada":"2022-02-01 11:55:45"}
        $f0 = strtotime(substr($final,0));#1643789914 aixo son segons desde inici unix
        $resta = $f0 - $i; #resto la quantitat de segons que han passat des del inici del temps unix
        $min = $resta/60;
        $hores = $min/60;
        /* $inici2 = date('Y-m-d H:i:s', $i);
        $fi2 = date('Y-m-d H:i:s', $f0); */
        /* $inicidata = new \DateTime($i);
        $inicidata->format('Y-m-d H:i:s'); */
        //$interval = $inici2->diffInMinutes($fi2);
        $activitat -> total = $hores;

        $activitat-> update();
        return view('home',compact('user'));    
        //return $hores;
    }
}
