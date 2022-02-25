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
use App\Models\Activitat;

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
        

        /* SELECT * FROM `pedidos` 
        INNER JOIN tasques ON pedidos.tasca = tasques.id
        WHERE treballador = 4; */

        //inner join solucionat
        $tasques = Pedido::join('tasques','pedidos.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id()])
                ->orderBy('pedidos.id','desc')->take(10)->get();

        return view('pedidos.pedidos',compact('user','tasques'));

        /* jaseando */
        $tot = Pedido::all();
        return response()->json($tot, 200);
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
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Activitat::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Preparació pedido'])->get();

        $idTasca = $nomTasca[0]->id;//Preparació pedido

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        //Model::whereNotNull('sent_at')
        

        //$tascaComprovacio = Pedido::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        //if ($tascaComprovacio == null){
            /*nou registre*/
            $pedido1 = Pedido::firstOrNew(
                [/* 'dia' => $diaFormat,  */'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca]
            );
            $pedido1->save();
        //} else {
        
        /* $tascaUltima = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();
        $tascaUltima->fiTasca = $horaFinal;
            $iniciSegs = strtotime($tascaUltima->iniciTasca);
            $acabadaSegs = strtotime($tascaUltima->fiTasca);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $tascaUltima-> total = $min;
            $tascaUltima-> fiTasca = $horaFinal;
            $tascaUltima->update(); */

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Pedido::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
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
        //}

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON pedidos.tasca = tasques.id
        where pedidos.id = 127 */
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims

        return view('pedidos.pedidos',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }


    public function storeRevPedido(Request $request){

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Activitat::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Revisió pedido'])->get();

        $idTasca = $nomTasca[0]->id;//revisio pedido

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

        //$tascaComprovacio = Pedido::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        //if ($tascaComprovacio == null){
            /*nou registre*/
            $revPedido = Pedido::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca]
            );
            $revPedido->save();
        //} else {
        
        /* $tascaUltima = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();
        $tascaUltima->fiTasca = $horaFinal;
            $iniciSegs = strtotime($tascaUltima->iniciTasca);
            $acabadaSegs = strtotime($tascaUltima->fiTasca);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $tascaUltima-> total = $min;
            $tascaUltima-> fiTasca = $horaFinal;
            $tascaUltima->update(); */

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
        //}

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('pedidos.pedidos',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }

    }

    public function storeExpedPedido(Request $request){

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Activitat::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Expedició'])->get();

        $idTasca = $nomTasca[0]->id;//Expedició

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

       
            /*nou registre*/
            $revPedido = Pedido::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca]
            );
            $revPedido->save();

            //busco l'ultima tasca creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Pedido::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Pedido::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();

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

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('pedidos.pedidos',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
    }

    public function storeSAFPedido(Request $request){
        
        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Activitat::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'SAF'])->get();

        $idTasca = $nomTasca[0]->id;//saf pedido
        

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

       
            /*nou registre*/
            $revPedido = Pedido::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca]
            );
            $revPedido->save();

            //busco l'ultima tasca creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Pedido::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Pedido::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();

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

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('pedidos.pedidos',compact('user','pedidos','tasques'));

        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
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


    /* ara mateix ja no s'esta utilitzant degut a que les tasques es poden parar per elles mateixes */
    public function stopPedidos(Request $request){
        $user = Auth::user();
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        /* s'hauria de parar totes les tasques, pero millor començar per la ultima */
        $tascaUltima = Pedido::where(['treballador'=> Auth::id()])->latest('id')->first();
        if ($tascaUltima->iniciTasca == $tascaUltima->fiTasca){
        $tascaUltima->fiTasca = $horaFinal;
            $iniciSegs = strtotime($tascaUltima->iniciTasca);
            $acabadaSegs = strtotime($tascaUltima->fiTasca);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $tascaUltima-> total = $min;
            $tascaUltima-> fiTasca = $horaFinal;
            $tascaUltima->update();
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON pedidos.tasca = tasques.id
        where pedidos.id = 127 */
        $pedidos = Pedido::join('tasques','pedidos.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('pedidos.id','desc')->take(10)->get();//agafo els 10 ultims
    
        return view('pedidos.pedidos',compact('user','pedidos','tasques'));
    }

    /**
     * busca si hi ha una tasca (pedido) inacabada
     * 
     * @param Request $request
     * 
     * @return response
     */
    public function checkTasques(Request $request){
        $user = Auth::user();
        //tasca where no hi ha total i per tant no esta acabada
        //$taskCheck = Pedido::whereIn('total', [null, 0.00])->where(['treballador'=> Auth::id()])->latest('updated_at')->first();

        $taskCheck = Pedido::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        //si no hi ha tasca ó ja esta acabada
        if ($taskCheck == null || $taskCheck->total > 0){
            return response()->json(0, 200);
        } else {
            return response()->json($taskCheck->tasca,200);
        }

    }


    /* accio inutil ara mateix */
    public function getTasques(Request $request){

        $tasks = Tasca::select('id','tasca')->get();

        return response()->json($tasks,200);
    }

}
