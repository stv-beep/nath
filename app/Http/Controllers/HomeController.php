<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
#necessaris per a activitats
use App\Models\Torn;
use App\Models\Jornada;
use App\Models\Tasca;
use App\Models\Comanda;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();#

        $jornada = now();//"2022-02-01T09:08:09.674363Z"
        $jorn = Carbon::parse($jornada)->setTimezone('Europe/Madrid')->format('Y-m-d');//2022-02-01
        $activitat = Torn::where(['treballador' => $user->id])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        $dia = Jornada::where(['treballador' => $user->id])->orderBy('id','desc')->take(5)->get();
        //inner join solucionat
        $tasques = Comanda::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id()])
                ->orderBy('activitats.id','desc')->take(10)->get();
        
        return view('home', compact('user','activitat','dia','tasques'));
    }
}
