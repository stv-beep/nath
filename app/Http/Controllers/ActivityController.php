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
use App\Models\Activity;

class ActivityController extends Controller
{
    public function storeActivity(Request $request)
    {
        /* task type */
        $task = $request->task;
        if($task <= 4){//pedidos
            $tipus = TaskType::where(['tipus' => 'Pedidos'])->get();
        } else if($task >= 5 && $task <= 8){//recepciones
            $tipus = TaskType::where(['tipus' => 'Recepciones'])->get();
        } else if($task >= 9 && $task <= 12){//reoperaciones
            $tipus = TaskType::where(['tipus' => 'Reoperaciones'])->get();
        } else if($task >= 13 && $task <= 14){//inventario
            $tipus = TaskType::where(['tipus' => 'Inventario'])->get();
        }

        //$tipus = TaskType::where(['tipus' => 'Inventario'])->get();
        $tipus = $tipus[0]->id;

        $user = Auth::user();
        //comprovacio de si la jornada esta iniciada
        $tornComprovacio = Torn::where(['treballador'=> Auth::id(), 'total'=> null])->latest('updated_at')->first();
        if (!($tornComprovacio == null)){//si la jornada no esta acabada

        //$nomTasca = Tasca::where(['tasca' => 'Compactar'])->get();

        //$idTasca = $nomTasca[0]->id;//Compactar

        $diaFormat = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d');

        $horaInici = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        $horaFinal = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
        

            /*nou registre*/
            $Inv1 = Inventari::firstOrNew(
                ['treballador'=> Auth::id()],
                ['iniciTasca' => $horaInici,'fiTasca' => $horaFinal, 'tasca' => $request->task, 'geolocation' => $request->x, 
                'info' => $request->info]//device info
            );
            $Inv1->save();

        //busco l'ultima tasca del treballador creada. Pot ser la de dalt o una ja feta
        $ultimaTasca = Inventari::where(['treballador'=> Auth::id()])->latest('id')->first();

        if($ultimaTasca->iniciTasca == $ultimaTasca->fiTasca){//si es una tasca nomes començada
            $invUpdate = Inventari::updateOrCreate(
                [/* 'dia'=> $diaFormat,  */'treballador'=> Auth::id(),'tasca' => $request->task, 'iniciTasca'=> $ultimaTasca->iniciTasca],
                ['tasca' => $request->task, 'dia'=> $diaFormat, 'treballador'=> Auth::id(), 'fiTasca'=> Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s')]
            );
            
            $inventari = Inventari::where(['dia'=> $diaFormat, 'treballador'=> Auth::id(), 'tasca' =>$request->task])->latest('id')->first();
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
            $newInv->tasca=$request->task;
            $newInv->dia=$diaFormat;
            $hour = Carbon::parse(now())->setTimezone('Europe/Madrid')->format('Y-m-d H:i:s');
            $newInv-> iniciTasca = $hour;
            $newInv-> fiTasca = $hour;
            $newInv->save();
            $nInv = Inventari::where(['treballador'=> Auth::id(), 'tasca' => $request->task])->latest('id')->first();
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
