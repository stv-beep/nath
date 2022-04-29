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
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function create() /* Tenir en compte que la taula 'activitats' engloba a totes les tasques que es completen */
    {
        $user = Auth::user();
        
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;

        //inner join solucionat
        $tasques = Comanda::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.comandes',compact('user','tasques'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)//preparacion pedido
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Preparación pedido'])->get();

        $idTasca = $nomTasca[0]->id;//Preparació pedido

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $pedido1 = Comanda::firstOrNew(
                [/* 'dia' => $diaFormat,  */'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $pedido1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Comanda::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $pedidoUpdate = Comanda::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $pedido = Comanda::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
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
            $pedido->info=$request->info;
            $pedido->update();
            //task finished

        } else {//si es una tasca acabada, en fem una de nova

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            $nouPedido->info=$request->info;
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $pedidos = Comanda::join('tasques','activitats.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('activitats.id','desc')->take(10)->get();//agafo els 10 ultims

        return response()->json($nomTasca);
        return view('activities.comandes',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }


    public function storeRevPedido(Request $request){//revisio pedido
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        $tipus = $tipus[0]->id;
        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Revisión pedido'])->get();

        $idTasca = $nomTasca[0]->id;//revisio pedido

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

            /*nou registre*/
            $revPedido = Comanda::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x,
                'info' => $request->info]
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
            $pedido->info=$request->info;
            $pedido->update();
            //task finished

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            $nouPedido->info=$request->info;
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Comanda::join('tasques','activitats.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('activitats.id','desc')->take(10)->get();//agafo els 10 ultims
        return response()->json($nomTasca);
        return view('activities.comandes',compact('user','pedidos','tasques'));
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

        $nomTasca = Tasca::where(['tasca' => 'Expedición'])->get();

        $idTasca = $nomTasca[0]->id;//Expedició

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');

       
            /*nou registre*/
            $revPedido = Comanda::firstOrNew(
                ['dia' => $diaFormat, 'treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x,
                'info' => $request->info]
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
            $pedido->info = $request->info;
            $pedido->update();
            //task finished

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            $nouPedido->info = $request->info;
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Comanda::join('tasques','activitats.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('activitats.id','desc')->take(10)->get();//agafo els 10 ultims
        return response()->json($nomTasca);
        return view('activities.comandes',compact('user','pedidos','tasques'));
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
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x,
                'info' => $request->info]
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
            $pedido->info = $request->info;
            $pedido->update();
            //task finished

        } else {

            $nouPedido = new Comanda();
            $nouPedido->treballador=Auth::id();
            $nouPedido->tipusTasca=$tipus;//task type
            $nouPedido->info = $request->info;
            $nouPedido->geolocation=$request->x;
            $nouPedido->tasca=$idTasca;
            $nouPedido->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $nouPedido-> iniciTasca = $hour;
            $nouPedido-> fiTasca = $hour;
            $nouPedido->save();
            $nPedido = Comanda::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//s'hauria de fer un inner join per a mostrar el nom de la tasca i no la id
        $pedidos = Comanda::join('tasques','activitats.tasca', '=', 'tasques.id')
        ->where(['treballador' =>  Auth::id()])->orderBy('activitats.id','desc')->take(10)->get();//agafo els 10 ultims
        return response()->json($nomTasca);
        return view('activities.comandes',compact('user','pedidos','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
    }

    /**
     * busca si hi ha una tasca inacabada, UTILITZAT per a totes les tasques (comandes, recepcions, inventari...)
     * 
     * @param Request $request
     * 
     * @return response
     */
    public function checkTasques(Request $request){
        $user = Auth::user();
        //tasca where no hi ha total i per tant no esta acabada

        $taskCheck = Comanda::join('tasques','activitats.tasca', '=','tasques.id')
        ->where(['treballador' => Auth::id()])->latest('activitats.updated_at')->first();

        //si no hi ha tasca ó ja esta acabada
        if ($taskCheck == null || $taskCheck->total > 0){
            return response()->json(0, 200);
        } else {
            return response()->json($taskCheck,200);
        }

    }
}
