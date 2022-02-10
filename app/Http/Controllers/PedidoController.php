<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent;
use Carbon\Carbon;
use \DateTime;
use App\Models\Jornada;
use App\Models\User;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('pedidos.pedidos',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        //$pedido = new Pedido();

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

        
        /*nou registre*/
        $pedido = Pedido::firstOrCreate(//busco el registre concret, i si no el troba, el creo
            ['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => 1]
        );

        $tasca = Pedido::where(['dia' => $diaFormat, 'treballador' => Auth::id()])->latest()->first();
        //return $tasca;
        if ($tasca->total == 0.00 || $tasca->total == null || $tasca->created_at == null || $tasca->created_at == $tasca->updated_at){//si no hi ha total o, inici = final
            

            $pedido = Pedido::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => 1 ],
                ['tasca' => 1, 'dia'=> $diaFormat, 'treballador'=> Auth::id(),'updated_at'=> $horaFinal]
            );

            $tasca = Pedido::where(['dia' => $diaFormat, 'treballador' => Auth::id()])->latest()->first();
            $iniciada = $tasca->created_at;
            $acabada = $tasca->updated_at;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $pedido-> total = $min;
            $pedido->update();
            
            
        } else { //si hi ha diferencia entre inici i final, i per tant, se te que crear un registre nou
            
            $pedido = new Pedido();
            $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');
            $pedido->dia = $diaFormat;
            $pedido-> treballador = $user->id;
            $pedido-> tasca = 1;
            $pedido->save();
        }
        
        return view('pedidos.pedidos',compact('user'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
