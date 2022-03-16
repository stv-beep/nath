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

class ComandaController extends Controller
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
        
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;

        //inner join solucionat
        $tasques = Comanda::join('tasques','comandes.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id()])
                ->orderBy('comandes.id','desc')->take(10)->get();

        return view('comandes.comandes',compact('user','tasques'));

        /* jaseando */
        $tot = Comanda::all();
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
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Preparació comanda'])->get();

        $idTasca = $nomTasca[0]->id;//Preparació pedido

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        //Model::whereNotNull('sent_at')
        

        //$tascaComprovacio = Comanda::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        //if ($tascaComprovacio == null){
            /*nou registre*/
            $pedido1 = Comanda::firstOrNew(
                [/* 'dia' => $diaFormat,  */'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x]
            );
            $pedido1->save();
        //} else {
        
        /* $tascaUltima = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();
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
        $ultimaTasca = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Comanda::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Comanda::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
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
            $pedido->tipusTasca=$tipus;//task type
            //$pedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);//getting the hostname of the client
            $pedido->update();
            //return $pedido;
            //echo 'tasca acabada';

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            //$nouPedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //echo 'tasca començada';
        }
        //}

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON comandes.tasca = tasques.id
        where comandes.id = 127 */
        $pedidos = Comanda::join('tasques','comandes.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('comandes.id','desc')->take(10)->get();//agafo els 10 ultims

        return view('comandes.comandes',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }


    public function storeRevPedido(Request $request){
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;
        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Revisió comanda'])->get();

        $idTasca = $nomTasca[0]->id;//revisio pedido

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

        //$tascaComprovacio = Comanda::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
        //if ($tascaComprovacio == null){
            /*nou registre*/
            $revPedido = Comanda::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x]
            );
            $revPedido->save();
        //} else {
        
        /* $tascaUltima = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();
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
        /* $revPedido = Comanda::firstOrNew(
            ['dia' => $diaFormat, 'treballador'=> Auth::id()],
            ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca]
        );
        $revPedido->save();
 */
        //busco l'ultima tasca creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Comanda::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Comanda::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            /* $iniciada = $pedido->created_at;
            $acabada = $pedido->updated_at; */
            $iniciada = $pedido->iniciTasca;
            $acabada = $pedido->fiTasca;
            $pedido->tipusTasca=$tipus;//task type
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $pedido-> total = $min;
            $pedido-> fiTasca = $horaFinal;
            //$pedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $pedido->update();
            //return $pedido;
            //echo 'tasca acabada';

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            //$nouPedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //echo 'tasca començada';
        }
        //}

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Comanda::join('tasques','comandes.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('comandes.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('comandes.comandes',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }

    }

    public function storeExpedPedido(Request $request){
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;
        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Expedició'])->get();

        $idTasca = $nomTasca[0]->id;//Expedició

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

       
            /*nou registre*/
            $revPedido = Comanda::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x]
            );
            $revPedido->save();

            //busco l'ultima tasca creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Comanda::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Comanda::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();

            $iniciada = $pedido->iniciTasca;
            $acabada = $pedido->fiTasca;
            $pedido->tipusTasca=$tipus;//task type
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $pedido-> total = $min;
            $pedido-> fiTasca = $horaFinal;
            //$pedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $pedido->update();
            //return $pedido;
            //echo 'tasca acabada';

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            //$nouPedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //echo 'tasca començada';
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Comanda::join('tasques','comandes.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('comandes.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('comandes.comandes',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
    }

    public function storeSAFPedido(Request $request){
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;
        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'SAF'])->get();

        $idTasca = $nomTasca[0]->id;//saf pedido
        

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

       
            /*nou registre*/
            $revPedido = Comanda::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x]
            );
            $revPedido->save();

            //busco l'ultima tasca creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Comanda::updateOrCreate(
                ['dia'=> $diaFormat, 'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Comanda::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();

            $iniciada = $pedido->iniciTasca;
            $acabada = $pedido->fiTasca;
            $pedido->tipusTasca=$tipus;//task type
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $pedido-> total = $min;
            $pedido-> fiTasca = $horaFinal;
            //$pedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $pedido->update();
            //return $pedido;
            //echo 'tasca acabada';

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            //$nouPedido->hostname=gethostbyaddr($_SERVER['REMOTE_ADDR']);
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //echo 'tasca començada';
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Comanda::join('tasques','comandes.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('comandes.id','desc')->take(10)->get();//agafo els 10 ultims
        return view('comandes.comandes',compact('user','pedidos','tasques'));

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
        $tascaUltima = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();
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
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON comandes.tasca = tasques.id
        where comandes.id = 127 */
        $pedidos = Comanda::join('tasques','comandes.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('comandes.id','desc')->take(10)->get();//agafo els 10 ultims
    
        return view('comandes.comandes',compact('user','pedidos','tasques'));
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
        //$taskCheck = Comanda::whereIn('total', [null, 0.00])->where(['treballador'=> Auth::id()])->latest('updated_at')->first();

        $taskCheck = Comanda::where(['treballador'=> Auth::id()])->latest('updated_at')->first();
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
