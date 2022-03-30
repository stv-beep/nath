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

         /* comandes */
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
         //recepcions
         $tasca = new Tasca();
         $tasca->tasca ='Recepcio1';
         $tasca->tipusTasca = 2;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Recepcio2';
         $tasca->tipusTasca = 2;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Reoperacio1';
         $tasca->tipusTasca = 2;
         $tasca->save();

         Jornada::factory(200)->create();

    }
}
