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
use App\Models\Reoperacio;

class ReoperacionsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /* Suposo que la taula de "comandes" hauria de canviar de nom per a englobar totes les tasques
        i d'esta forma a cada tasca posar un camp de tipus de tasca per a aixi imprimir lo que toca a cada una.
        Aixi, quan s'hagi de comprovar si hi ha alguna tasca inacabada, sera dins de la mateixa taula, i no se
        tindra que mirar a cadascuna de les probables taules (comandes, recepcions, reoperacions, inventari) */

        /* task type */
        $tipus = TaskType::where(['tipus' => 'Reoperaciones'])->get();
        $tipus = $tipus[0]->id;

        //inner join solucionat
        $tasques = Reoperacio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.reoperacions',compact('user','tasques'));

        //jaseando
        $tot = Reoperacio::all();
        return response()->json($tot, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeLecturaProd(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Reoperaciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Lectura producto'])->get();

        $idTasca = $nomTasca[0]->id;//Lectura producto

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $reop1 = Reoperacio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $reop1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Reoperacio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $reopUpdate = Reoperacio::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $reoperacio = Reoperacio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $reoperacio->iniciTasca;
            $acabada = $reoperacio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $reoperacio-> total = $min;
            $reoperacio-> fiTasca = $horaFinal;
            $reoperacio->tipusTasca=$tipus;//task type
            $reoperacio->info=$request->info;
            $reoperacio->update();
            //task finished

        } else {

            $newReop = new Reoperacio();
            $newReop->treballador=Auth::id();
            $newReop->tipusTasca=$tipus;//task type
            $newReop->info=$request->info;
            $newReop->geolocation=$request->x;
            $newReop->tasca=$idTasca;
            $newReop->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newReop-> iniciTasca = $hour;
            $newReop-> fiTasca = $hour;
            $newReop->save();
            $nReop = Reoperacio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Reoperacio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.reoperacions',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeEmbolsar(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Reoperaciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Embolsar'])->get();

        $idTasca = $nomTasca[0]->id;//Embolsar

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $reop1 = Reoperacio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $reop1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Reoperacio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $reopUpdate = Reoperacio::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $reoperacio = Reoperacio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $reoperacio->iniciTasca;
            $acabada = $reoperacio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $reoperacio-> total = $min;
            $reoperacio-> fiTasca = $horaFinal;
            $reoperacio->tipusTasca=$tipus;//task type
            $reoperacio->info=$request->info;
            $reoperacio->update();
            //task finished

        } else {

            $newReop = new Reoperacio();
            $newReop->treballador=Auth::id();
            $newReop->tipusTasca=$tipus;//task type
            $newReop->info=$request->info;
            $newReop->geolocation=$request->x;
            $newReop->tasca=$idTasca;
            $newReop->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newReop-> iniciTasca = $hour;
            $newReop-> fiTasca = $hour;
            $newReop->save();
            $nReop = Reoperacio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Reoperacio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.reoperacions',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeEtiquetar(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Reoperaciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Etiquetar'])->get();

        $idTasca = $nomTasca[0]->id;//Etiquetar

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $reop1 = Reoperacio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $reop1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Reoperacio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $reopUpdate = Reoperacio::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $reoperacio = Reoperacio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $reoperacio->iniciTasca;
            $acabada = $reoperacio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $reoperacio-> total = $min;
            $reoperacio-> fiTasca = $horaFinal;
            $reoperacio->tipusTasca=$tipus;//task type
            $reoperacio->info=$request->info;
            $reoperacio->update();
            //task finished

        } else {

            $newReop = new Reoperacio();
            $newReop->treballador=Auth::id();
            $newReop->tipusTasca=$tipus;//task type
            $newReop->info=$request->info;
            $newReop->geolocation=$request->x;
            $newReop->tasca=$idTasca;
            $newReop->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newReop-> iniciTasca = $hour;
            $newReop-> fiTasca = $hour;
            $newReop->save();
            $nReop = Reoperacio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Reoperacio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.reoperacions',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOtros(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Reoperaciones'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Otros'])->get();

        $idTasca = $nomTasca[0]->id;//Otros

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $reop1 = Reoperacio::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $reop1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Reoperacio::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $reopUpdate = Reoperacio::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $reoperacio = Reoperacio::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $reoperacio->iniciTasca;
            $acabada = $reoperacio->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $reoperacio-> total = $min;
            $reoperacio-> fiTasca = $horaFinal;
            $reoperacio->tipusTasca=$tipus;//task type
            $reoperacio->info=$request->info;
            $reoperacio->update();
            //task finished

        } else {

            $newReop = new Reoperacio();
            $newReop->treballador=Auth::id();
            $newReop->tipusTasca=$tipus;//task type
            $newReop->info=$request->info;
            $newReop->geolocation=$request->x;
            $newReop->tasca=$idTasca;
            $newReop->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newReop-> iniciTasca = $hour;
            $newReop-> fiTasca = $hour;
            $newReop->save();
            $nReop = Reoperacio::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Reoperacio::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.reoperacions',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }

}
