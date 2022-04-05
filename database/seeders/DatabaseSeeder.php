<?php

namespace Database\Seeders;
use \App\Models\User;
use \App\Models\Tasca;
use \App\Models\Torn;
use \App\Models\TaskType;
use \App\Models\Jornada;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         User::factory(50)->create();

         //task type
         $tasktype = new TaskType();
         $tasktype->tipus = 'Pedidos';
         $tasktype->save();
         $tasktype = new TaskType();
         $tasktype->tipus = 'Recepciones';
         $tasktype->save();
         $tasktype = new TaskType();
         $tasktype->tipus = 'Reoperaciones';
         $tasktype->save();
         $tasktype = new TaskType();
         $tasktype->tipus = 'Inventario';
         $tasktype->save();

         //pedidos
         $tasca = new Tasca();
         $tasca->tasca ='Preparación pedido';
         $tasca->tipusTasca = 1;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Revisión pedido';
         $tasca->tipusTasca = 1;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Expedición';
         $tasca->tipusTasca = 1;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='SAF';
         $tasca->tipusTasca = 1;
         $tasca->save();
         //recepciones
         $tasca = new Tasca();
         $tasca->tasca ='Descarga camión';
         $tasca->tipusTasca = 2;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Lectura entrada';
         $tasca->tipusTasca = 2;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Control de calidad';
         $tasca->tipusTasca = 2;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Ubicar producto';
         $tasca->tipusTasca = 2;
         $tasca->save();
         //reoperaciones
         $tasca = new Tasca();
         $tasca->tasca ='Lectura producto';
         $tasca->tipusTasca = 3;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Embolsar';
         $tasca->tipusTasca = 3;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Etiquetar';
         $tasca->tipusTasca = 3;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Otros';
         $tasca->tipusTasca = 3;
         $tasca->save();
         //Inventario
         $tasca = new Tasca();
         $tasca->tasca ='Compactar';
         $tasca->tipusTasca = 4;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Inventariar';
         $tasca->tipusTasca = 4;
         $tasca->save();

         Jornada::factory(200)->create();

    }
}
