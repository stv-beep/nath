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
         User::factory(200)->create();

         //task type
         $tasktype = new TaskType();
         $tasktype->tipus = 'Pedidos';
         $tasktype->save();
         $tasktype = new TaskType();
         $tasktype->tipus = 'Recepcions';
         $tasktype->save();
         $tasktype = new TaskType();
         $tasktype->tipus = 'Reoperacions';
         $tasktype->save();
         $tasktype = new TaskType();
         $tasktype->tipus = 'Inventari';
         $tasktype->save();

         /* comandes */
         $tasca = new Tasca();
         $tasca->tasca ='Preparació comanda';
         $tasca->tipusTasca = 1;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Revisió comanda';
         $tasca->tipusTasca = 1;
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Expedició';
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

         Jornada::factory(1000)->create();

    }
}
