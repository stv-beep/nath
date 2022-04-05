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
use App\Models\Inventari;

class InventariController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        /* Suposo que la taula de "comandes" hauria de canviar de nom per a englobar totes les tasques
        i d'esta forma a cada tasca posar un camp de tipus de tasca per a aixi imprimir lo que toca a cada una.
        Aixi, quan s'hagi de comprovar si hi ha alguna tasca inacabada, sera dins de la mateixa taula, i no se
        tindra que mirar a cadascuna de les probables taules (comandes, recepcions, reoperacions, inventari) */

        /* task type */
        $tipus = TaskType::where(['tipus' => 'Inventario'])->get();
        $tipus = $tipus[0]->id;

        //inner join solucionat
        $tasques = Inventari::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.inventari',compact('user','tasques'));

        //jaseando
        $tot = Inventari::all();
        return response()->json($tot, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCompactar(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Inventario'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Compactar'])->get();

        $idTasca = $nomTasca[0]->id;//Compactar

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $Inv1 = Inventari::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $Inv1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Inventari::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $invUpdate = Inventari::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $inventari = Inventari::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $inventari->iniciTasca;
            $acabada = $inventari->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $inventari-> total = $min;
            $inventari-> fiTasca = $horaFinal;
            $inventari->tipusTasca=$tipus;//task type
            $inventari->info=$request->info;
            $inventari->update();
            //task finished

        } else {

            $newInv = new Inventari();
            $newInv->treballador=Auth::id();
            $newInv->tipusTasca=$tipus;//task type
            $newInv->info=$request->info;
            $newInv->geolocation=$request->x;
            $newInv->tasca=$idTasca;
            $newInv->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newInv-> iniciTasca = $hour;
            $newInv-> fiTasca = $hour;
            $newInv->save();
            $nInv = Inventari::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Inventari::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.inventari',compact('user','tasques'));
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
    public function storeInventariar(Request $request)
    {
        /* task type */
        $tipus = TaskType::where(['tipus' => 'Inventario'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        $nomTasca = Tasca::where(['tasca' => 'Inventariar'])->get();

        $idTasca = $nomTasca[0]->id;//Inventariar

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $Inv1 = Inventari::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $idTasca, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $Inv1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Inventari::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $invUpdate = Inventari::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $idTasca, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $idTasca, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $inventari = Inventari::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            $iniciada = $inventari->iniciTasca;
            $acabada = $inventari->fiTasca;
            $iniciSegs = strtotime($iniciada);
            $acabadaSegs = strtotime($acabada);
            $resta = $acabadaSegs - $iniciSegs;
            $min = $resta/60;
            $hores = $min/60;
            $inventari-> total = $min;
            $inventari-> fiTasca = $horaFinal;
            $inventari->tipusTasca=$tipus;//task type
            $inventari->info=$request->info;
            $inventari->update();
            //task finished

        } else {

            $newInv = new Inventari();
            $newInv->treballador=Auth::id();
            $newInv->tipusTasca=$tipus;//task type
            $newInv->info=$request->info;
            $newInv->geolocation=$request->x;
            $newInv->tasca=$idTasca;
            $newInv->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newInv-> iniciTasca = $hour;
            $newInv-> fiTasca = $hour;
            $newInv->save();
            $nInv = Inventari::where(['treballador'=> Auth::id(), 'tasca' => $idTasca])->latest('id')->first();
            //task started
        }

        $tasques = Tasca::all();//inner join per a mostrar el nom de la tasca i no la id
        /* SELECT * FROM `pedidos` INNER JOIN tasques ON activitats.tasca = tasques.id
        where activitats.id = 127 */
        $tasques = Inventari::join('tasques','activitats.tasca', '=','tasques.id')
                ->where(['treballador' =>  Auth::id(), 'activitats.tipusTasca'=> $tipus])
                ->orderBy('activitats.id','desc')->take(10)->get();

        return view('activities.inventari',compact('user','tasques'));
        } else {//si la jornada no esta iniciada torno false
            return response()->json(false, 200);
            
        }
          
    }
}
