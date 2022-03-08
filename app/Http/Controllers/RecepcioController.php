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
use App\Models\Recepcio;

class RecepcioController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        

        /* Suposo que la taula de "comandes" hauria de canviar de nom per a englobar totes les tasques
        i d'esta forma a cada tasca posar un camp de tipus de tasca per a aixi imprimir lo que toca a cada una.
        Aixi, quan s'hagi de comprovar si hi ha alguna tasca inacabada, sera dins de la mateixa taula, i no se
        tindra que mirar a cadascuna de les probables taules (comandes, recepcions, reoperacions, inventari) */

        //inner join solucionat
        /* $tasques = Recepcio::join('tasques','comandes.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id()])
                ->orderBy('comandes.id','desc')->take(10)->get();

        return view('comandes.comandes',compact('user','tasques'));

        //jaseando
        $tot = Recepcio::all();
        return response()->json($tot, 200); */
    }
}
