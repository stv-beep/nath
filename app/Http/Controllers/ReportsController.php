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
            return response()->json($dia);
        }
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
        $query = Jornada::join('users','jornades.treballador','=','users.id')
        ->where(['treballador' => $treballador,'dia'=>$date])->get();

        return response()->json($query);
    }

    public function shiftQuery(Request $request){
        $treballador = $request->worker;
        $data = $request->dia;
        $date = Carbon::parse($data)->format('Y-m-d');
        $query = Torn::join('users','torns.treballador','=','users.id')
        ->where(['treballador' => $treballador,'jornada'=>$date])->get();

        return response()->json($query);
    }

    public function taskQuery(Request $request){
        $treballador = $request->worker;
        $data = $request->dia;
        $date = Carbon::parse($data)->format('Y-m-d');
        $query = Comanda::join('users','activitats.treballador','=','users.id')
        ->join('tasques','activitats.tasca', '=','tasques.id')
        ->where(['treballador' => $treballador,'dia'=>$date])->get();

        return response()->json($query);
    }

    public function getEmployees(Request $request){

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


    /* EDIT USERS */
    /**
     * index users
     * @return [type]
     */
    public function indexUsers(){
        $user = Auth::user();
        
        if ($user->administrador == true){
            $users = User::all();
            return view('admin.usuaris',compact('user','users'));
        }
    }

    public function showUsers(){
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\task  $task
     * @return \Illuminate\Http\Response
     */
    public function editUser($usuari)
    {
        $u = User::findOrFail($usuari);
        return response()->json($u);
    }

    public function updateUser(Request $request, User $usuari){
        $usuari->id_odoo_nath = $request->id_nath;
        $usuari->id_odoo_tuctuc = $request->id_tuctuc;
        $usuari->administrador = $request->admin;
        $usuari->magatzem = $request->magatzem;
        $usuari->DNI = $request->dni;
        $usuari->update();
    }

    public function createUser(Request $request){

        $searchingU = User::where(['username'=> $request->username])->get();
        $searchingDNI = User::where(['DNI'=> $request->dni])->get();
        $u = count($searchingU);
        $dni = count($searchingDNI);
        if ($u < 1 & $dni < 1){//si username i dni no existeixen

        $usuari = new User();
        $usuari->username = $request->username;
        $usuari->name = $request->name;
        $usuari->DNI = $request->dni;
        $usuari->magatzem = $request->magatzem;
        $usuari->password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
        $usuari->id_odoo_nath = $request->id_nathCreate;
        $usuari->id_odoo_tuctuc = $request->id_tuctucCreate;
        $usuari->save(); 

        $lastUser = User::where(['username'=> $request->username])->latest()->first();
            if ($lastUser != NULL || $lastUser != '[]') {
                return response()->json(true);
            } else {
                return response()->json(['message' => 'error message'], 500);
            }
        } else {//si username i dni ja existeixen
            return response()->json(false);
        }
    }

    public function deleteUser($user){
        $u = User::findOrFail($user);

        if ($u->administrador==1){//if administrador
            $allUsers = User::where(['administrador'=> 1])->get();//all admins
            if (count($allUsers)<2) {//if only 1 admin, its you and can't delete yourself
                return response()->json(false);
            } else {//if more than one, so you can delete other admin
                $u->delete(); 
                return response()->json(true);
            }

        } else {
            $u->delete();
            return response()->json(true);
        }
        

    }
    
}
