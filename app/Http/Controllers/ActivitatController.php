<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitat;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


class ActivitatController extends Controller
{
    public function create(){
        $user = Auth::user();
        return view('activitat-form',compact('user'));
    }


    public function store(Request $request){
        $activitat = new Activitat();
        $activitat-> id_treballador = $request->input("treballador");
        //$activitat-> id_treballador =$user;
        //$activitat->total_cron = $request->total_cron;
        $activitat-> total_cron = $request->input("total_cron");
        $activitat-> inici_jornada = $request->input("inici-jornada");

        $activitat-> fi_jornada = $request->input("final-jornada");

        
        $inici = new \Carbon\Carbon($request->input("inici-jornada"));//diferencia de hores

        $final = new \Carbon\Carbon($request->input("final-jornada"));

        

        
        $resta=$inici->diffInMinutes($final);//resto inici jornada i final en minuts

        $activitat->total = $resta/60;//convertixo a hores
        $activitat->save();
        $activitat = Activitat::all();
        return view("home");
        //return $request->all();
}
}