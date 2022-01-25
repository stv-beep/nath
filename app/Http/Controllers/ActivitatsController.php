<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitats;
use Illuminate\Support\Facades\DB;

class ActivitatsController extends Controller
{

    public function create(){
        return view('activitat-form');
    }


    public function store(Request $request){
        $activitat = new Activitats();
        $activitat-> treballador = $request->input("input-treballador");
        //$activitat->total_cron = $request->total_cron;
        $activitat-> total_cron = $request->input("total_cron");
        $activitat-> inici_jornada = $request->input("inici-jornada");

        $activitat-> fi_jornada = $request->input("final-jornada");

        
        $inici = new \Carbon\Carbon($request->input("inici-jornada"));//diferencia de hores

        $final = new \Carbon\Carbon($request->input("final-jornada"));

        

        
        $resta=$inici->diffInMinutes($final);//resto inici jornada i final en minuts

        $activitat->total = $resta/60;//convertixo a hores
        $activitat->save();
        $activitat = Activitats::all();
        return view("activitat-form");
        //return $request->all();



        /* $activitat = DB::statement('call activitats(?,?,?,?,?)',
            [$request->treballador,$request->total_cron,$request->inici_jornada,$request->fi_jornada,$request->total]);
            return back(); */
    }
}
