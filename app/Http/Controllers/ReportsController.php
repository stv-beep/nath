<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comanda;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Carbon\Carbon;
use \DateTime;
use App\Models\Jornada;
use App\Models\User;
use App\Models\Tasca;
use App\Models\Torn;
use App\Models\TaskType;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        //buscant les jornades amb els noms dels treballadors
        if ($user->administrador == true){
            $dia = Jornada::join('users','jornades.treballador','=','users.id')->orderBy('jornades.id','desc')->get();
        }   


        return view('admin.reports',compact('user','dia'));
    }
}
