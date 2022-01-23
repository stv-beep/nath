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
       // $activitat-> total
        $activitat-> inici_jornada = $request->input("inici-jornada");
        $activitat-> fi_jornada = $request->input("final-jornada");
        $activitat->save();
        $activitat = Activitat::all();
        return view("activitat-form");
        //return $request->all();
    }
}
