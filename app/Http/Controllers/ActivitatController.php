<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitat;

class ActivitatController extends Controller
{
    public function create(Request $request){
        $activitat = new Activitat();
       // $activitat->camp = $request->camp;
        $activitat->camp = $request->input("camp");
        $activitat->save();
        $activitat = Activitat::all();
        return view("activitat-form");
    }
}
