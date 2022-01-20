<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProvaController extends Controller
{
    function guardar(Request $request){

        $prova = new Prova;
        $prova->vehiculo = $request->input("camp");
        $prova->save();
        $prova = Prova::all();
        return view("activitat-form");
    }
}
