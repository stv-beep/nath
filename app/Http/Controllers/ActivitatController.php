<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitat;

class ActivitatController extends Controller
{

    public function create(){
        return view('activitat-form');
    }


    public function store(Request $request){
        $activitat = new Activitat();
        $activitat-> treballador = $request->input("input-treballador");
        //$activitat->camp = $request->camp;
        /* $activitat-> inici_jornada = now();
        $activitat-> fi_jornada = now(); */

        $activitat-> inici_jornada = $request->input("inici-jornada");
        $activitat-> fi_jornada = $request->input("final-jornada");

        /*diferencia de hores*/
        $carbon1 = new \Carbon\Carbon($request->input("inici-jornada"));
        $carbon2 = new \Carbon\Carbon($request->input("final-jornada"));

        $resta=$carbon1->diffInHours($carbon2);

        $activitat->total = $resta;
        $activitat->save();
        $activitat = Activitat::all();
        return view("activitat-form");
        //return $request->all();
    }
}
