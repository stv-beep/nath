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
use App\Models\Recepcio;

class RecepcioController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /* Suposo que la taula de "comandes" hauria de canviar de nom per a englobar totes les tasques
        i d'esta forma a cada tasca posar un camp de tipus de tasca per a aixi imprimir lo que toca a cada una.
        Aixi, quan s'hagi de comprovar si hi ha alguna tasca inacabada, sera dins de la mateixa taula, i no se
        tindra que mirar a cadascuna de les probables taules (comandes, recepcions, reoperacions, inventari) */

        /* task type */
        $tipus = TaskType::where(['tipus' => 'Recepciones'])->get();
        $tipus = $tipus[0]->id;

        //inner join solucionat
        $tasques = Recepcio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.recepcions',compact('user','tasques'));

        //jaseando
        $tot = Recepcio::all();
        return response()->json($tot, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDescarga(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Recepciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Descarga camión'])->get();

        $idTasca = $nomTasca[0]->id;//Recepcio1

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $recepcio1 = Recepcio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $recepcio1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Recepcio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $recepUpdate = Recepcio::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $recepcio = Recepcio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $recepcio->iniciTasca;
            $acabada = $recepcio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $recepcio-> total = $min;
            $recepcio-> fiTasca = $horaFinal;
            $recepcio->tipusTasca=$tipus;//task type
            $recepcio->info=$request->info;
            $recepcio->update();
            //task finished

        } else {

            $newRecp = new Recepcio();
            $newRecp->treballador=Auth::id();
            $newRecp->tipusTasca=$tipus;//task type
            $newRecp->info=$request->info;
            $newRecp->geolocation=$request->x;
            $newRecp->tasca=$idTasca;
            $newRecp->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newRecp-> iniciTasca = $hour;
            $newRecp-> fiTasca = $hour;
            $newRecp->save();
            $nRecp = Recepcio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Recepcio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.recepcions',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }


    public function storeEntrada(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Recepciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Lectura entrada'])->get();

        $idTasca = $nomTasca[0]->id;//Recepcio2

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $recepcio2 = Recepcio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $recepcio2->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Recepcio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $recep2Update = Recepcio::updateOrCreate(
                ['treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $recepcio = Recepcio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $recepcio->iniciTasca;
            $acabada = $recepcio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $recepcio-> total = $min;
            $recepcio-> fiTasca = $horaFinal;
            $recepcio->tipusTasca=$tipus;//task type
            $recepcio->info=$request->info;
            $recepcio->update();
            //task finished

        } else {

            $newRecp = new Recepcio();
            $newRecp->treballador=Auth::id();
            $newRecp->tipusTasca=$tipus;//task type
            $newRecp->info=$request->info;
            $newRecp->geolocation=$request->x;
            $newRecp->tasca=$idTasca;
            $newRecp->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newRecp-> iniciTasca = $hour;
            $newRecp-> fiTasca = $hour;
            $newRecp->save();
            $nRecp = Recepcio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Recepcio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.comandes',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }


    public function storeControlCalidad(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Recepciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Control de calidad'])->get();

        $idTasca = $nomTasca[0]->id;//Recepcio2

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $recepcio2 = Recepcio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $recepcio2->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Recepcio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $recep2Update = Recepcio::updateOrCreate(
                ['treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $recepcio = Recepcio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $recepcio->iniciTasca;
            $acabada = $recepcio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $recepcio-> total = $min;
            $recepcio-> fiTasca = $horaFinal;
            $recepcio->tipusTasca=$tipus;//task type
            $recepcio->info=$request->info;
            $recepcio->update();
            //task finished

        } else {

            $newRecp = new Recepcio();
            $newRecp->treballador=Auth::id();
            $newRecp->tipusTasca=$tipus;//task type
            $newRecp->info=$request->info;
            $newRecp->geolocation=$request->x;
            $newRecp->tasca=$idTasca;
            $newRecp->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newRecp-> iniciTasca = $hour;
            $newRecp-> fiTasca = $hour;
            $newRecp->save();
            $nRecp = Recepcio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Recepcio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.comandes',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }

    public function storeUbicarProducto(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Recepciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Ubicar producto'])->get();

        $idTasca = $nomTasca[0]->id;//Recepcio2

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $recepcio2 = Recepcio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $recepcio2->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Recepcio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $recep2Update = Recepcio::updateOrCreate(
                ['treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $recepcio = Recepcio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $recepcio->iniciTasca;
            $acabada = $recepcio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $recepcio-> total = $min;
            $recepcio-> fiTasca = $horaFinal;
            $recepcio->tipusTasca=$tipus;//task type
            $recepcio->info=$request->info;
            $recepcio->update();
            //task finished

        } else {

            $newRecp = new Recepcio();
            $newRecp->treballador=Auth::id();
            $newRecp->tipusTasca=$tipus;//task type
            $newRecp->info=$request->info;
            $newRecp->geolocation=$request->x;
            $newRecp->tasca=$idTasca;
            $newRecp->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newRecp-> iniciTasca = $hour;
            $newRecp-> fiTasca = $hour;
            $newRecp->save();
            $nRecp = Recepcio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Recepcio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.comandes',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }
}
