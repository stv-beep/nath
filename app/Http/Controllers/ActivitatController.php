<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
//use Illuminate\Database\Eloquent\Collection;

class ActivitatController extends Controller
{
    public function create(){
        $user = Auth::user();
        return view('activitat-form',compact('user'));
    }

    public function store(Request $request){
        $activitat = new Activitat();
        $activitat-> treballador = $request->input("treballador");
        //$activitat-> jornada = new \Carbon\Carbon(date('d-m-Y'));
        $activitat-> jornada = now();
        //$activitat-> total_cron = $request->input("total_cron");
        $activitat-> iniciJornada = $request->input("inici-jornada");

        //$activitat-> fiJornada = $request->input("final-jornada");

        /*
        $inici = new \Carbon\Carbon($request->input("inici-jornada"));//diferencia de hores

        $final = new \Carbon\Carbon($request->input("final-jornada"));

        

        
        $resta=$inici->diffInMinutes($final);//resto inici jornada i final en minuts

        $activitat->total = $resta/60;//convertixo a hores */
        $activitat->save();
        $activitat = Activitat::all();
        $user = Auth::user();
        return view('home',compact('user'));
        //return $request->all();
} 

    public function update(Request $request){
        $user = Auth::user();           
            
            //$activitat = Activitat::where(['jornada' => '2022-01-31', 'treballador' => '1'])->first();
            /* $activitat = Activitat::where(['treballador' => '1'])->first();

            
            $activitat->update([
                'fiJornada' => $request->input('final-Jornada'),
            ]);
 */
        $activitat = Activitat::find(3);
        $activitat-> fiJornada = $request->input("final-jornada");
        $activitat-> update();
            
        return view('home',compact('user'));

    }
}
