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
use App\Models\Tasca;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Pedido::where(['treballador' =>  Auth::id()])->orderBy('id','desc')->take(10)->get();//agafo els 10 ultims
        return view('pedidos.pedidos',compact('user','pedidos','tasques'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $idTasca = 1;//preparacio pedido
        $user = Auth::user();

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

        //Model::whereNotNull('sent_at')

        $tascaComprovacio = Pedido::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        if ($tascaComprovacio == null){
            /*nou registre*/
            $pedido1 = Pedido::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca,'iniciTasca'=> $horaInici,'fiTasca'=> $horaFinal]
            );
            $pedido1->save();
        } else {
        
        $tascaUltima = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();
        $tascaUltima->fiTasca = $horaFinal;
            $iniciSegs = strtotime($tascaUltima->iniciTasca);
            $acabadaSegs = strtotime($tascaUltima->fiTasca);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $tascaUltima-> total = $min;
            $tascaUltima-> fiTasca = $horaFinal;
            $tascaUltima->update();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Pedido::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Pedido::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            /* $iniciada = $pedido->created_at;
            $acabada = $pedido->updated_at; */
            $iniciada = $pedido->iniciTasca;
            $acabada = $pedido->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $pedido-> total = $min;
            $pedido-> fiTasca = $horaFinal;
            $pedido->update();
            //return $pedido;
            echo 'tasca acabada';

        } else {

            $nouPedido = new Pedido();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Pedido::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            echo 'tasca començada';
        }
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON pedidos.tasca = tasques.id
        where pedidos.id = 127 */
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims

        return view('pedidos.pedidos',compact('user','pedidos','tasques'));
          
    }


    public function storeRevPedido(Request $request){

        $idTasca = 2;//revisio pedido
        $user = Auth::user();

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

        $tascaComprovacio = Pedido::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        if ($tascaComprovacio == null){
            /*nou registre*/
            $revPedido = Pedido::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca,'iniciTasca'=> $horaInici,'fiTasca'=> $horaFinal]
            );
            $revPedido->save();
        } else {
        
        $tascaUltima = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();
        $tascaUltima->fiTasca = $horaFinal;
            $iniciSegs = strtotime($tascaUltima->iniciTasca);
            $acabadaSegs = strtotime($tascaUltima->fiTasca);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $tascaUltima-> total = $min;
            $tascaUltima-> fiTasca = $horaFinal;
            $tascaUltima->update();

        /*nou registre*/
        /* $revPedido = Pedido::firstOrNew(
            ['dia' => $diaFormat, 'treballador'=> Auth::id()],
            ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca]
        );
        $revPedido->save();
 */
        //busco l'ultima tasca creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Pedido::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Pedido::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            /* $iniciada = $pedido->created_at;
            $acabada = $pedido->updated_at; */
            $iniciada = $pedido->iniciTasca;
            $acabada = $pedido->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $pedido-> total = $min;
            $pedido-> fiTasca = $horaFinal;
            $pedido->update();
            //return $pedido;
            echo 'tasca acabada';

        } else {

            $nouPedido = new Pedido();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Pedido::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            echo 'tasca començada';
        }
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('pedidos.pedidos',compact('user','pedidos','tasques'));

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


    public function stopPedidos(Request $request){
        $user = Auth::user();

        /* s'hauria de parar totes les tasques, pero millor començar per la ultima */
        

        return view('pedidos.pedidos',compact('user'));
    }

}
