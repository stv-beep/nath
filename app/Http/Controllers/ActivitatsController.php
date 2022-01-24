<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitats;

class ActivitatsController extends Controller
{

    public function create(){
        return view('activitat-form');
    }


    public function store(Request $request){
        $activitat = new Activitats();
        $activitat-> treballador = $request->input("input-treballador");
        //$activitat->camp = $request->camp;
       
        /* $activitat-> inici_jornada = now();
        $activitat-> fi_jornada = now(); */
        $activitat-> inici_jornada = $request->input("inici-jornada");
        $activitat-> fi_jornada = $request->input("final-jornada");

        /*diferencia de hores*/
        $inici = new \Carbon\Carbon($request->input("inici-jornada"));
        $final = new \Carbon\Carbon($request->input("final-jornada"));

        /*resto inici jornada i final en minuts*/
        $resta=$inici->diffInMinutes($final);

        $activitat->total = $resta/60;//convertixo a hores
        $activitat->save();
        $activitat = Activitats::all();
        return view("activitat-form");
        //return $request->all();
    }
}
