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
        $activitat->camp = $request->input("input-treballador");
        //$activitat->camp = $request->camp;
        $activitat->save();
        $activitat = Activitat::all();
        return view("activitat-form");
        //return $request->all();
    }
}
