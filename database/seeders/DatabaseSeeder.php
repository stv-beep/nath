<?php

namespace Database\Seeders;
use \App\Models\User;
use \App\Models\Tasca;
use \App\Models\Torn;
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
         User::factory(3)->create();
         
         /* tasques, no fa falta factory */
         $tasca = new Tasca();
         $tasca->tasca ='Preparació comanda';
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Revisió comanda';
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='Expedició';
         $tasca->save();
         $tasca = new Tasca();
         $tasca->tasca ='SAF';
         $tasca->save();

    }
}
