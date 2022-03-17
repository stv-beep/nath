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
        
        if ($user->administrador == true){
            return view('admin.reports',compact('user'));
        }   
    }

    public function show(){
        $user = Auth::user();
        //buscant les jornades amb els noms dels treballadors
        if ($user->administrador == true){
            $dia = Jornada::join('users','jornades.treballador','=','users.id')->get();
        }
        return response()->json($dia);
    }

    public function twoDateQuery(Request $request){
        //SELECT * FROM `jornades` WHERE dia BETWEEN '2022-02-01' AND '2022-03-05' AND treballador=3;
        $treballador= $request->worker;
        $data1 = $request->dia1;
        $data2 = $request->dia2;
        $date1 = Carbon::parse($data1)->format('Y-m-d');
        $date2 = Carbon::parse($data2)->format('Y-m-d');
        //$query = Jornada::where(['treballador' => $treballador])->whereBetween('dia',[$date1, $date2])->get();
        $totals = Jornada::where(['treballador' => $treballador])->whereBetween('dia',[$date1, $date2])->get('total');

        $nomTreb = User::where(['id'=>$treballador])->get('name');
        $name = $nomTreb[0]->name;

        $n = count($totals);
            $suma = 0;
            for ($i = 0; $i < $n; $i++){
                $suma += $totals[$i]->total;
            }
        return response()->json([$name, $suma]);
    }

    /**
     * @param Request $request
     * 
     * @return [type]
     */
    public function completeQuery(Request $request){
        //SELECT * FROM `jornades` WHERE treballador=1 AND dia='2022-03-16';
        $treballador= $request->worker;
        $data = $request->dia;
        $date = Carbon::parse($data)->format('Y-m-d');
        $query = Jornada::join('users','jornades.treballador','=','users.id')->where(['treballador' => $treballador,'dia'=>$date])->get();

        return response()->json($query);
    }


    public function getEmployees(Request $request){

        //$employee = User::all();
        /* $employees = User::select('id',"name")
        ->where("name","LIKE","%{$request->input('query')}%")
        ->get();

        return response()->json($employees); */


        ###versio 2

        $query = $request->get('term','');

        $employees=User::where('name','LIKE','%'.$query.'%')->get();

        $data=array();
        foreach ($employees as $employee) {
            $data[]=array('value'=>$employee->name,'id'=>$employee->id);
        }
        if(count($data))
            return $data;
        else
            return ['value'=>'No Result Found','id'=>''];

    }
}
