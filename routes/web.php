<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivitatsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::view("inici-form","inici-form");
Route::view("activitat-form","activitat-form");


/*PROVES*/

Route::get('activitat-form',[ActivitatsController::class,'create']);
Route::post('activitat-form/store',[ActivitatsController::class,'store'])->name('activitat-form.store');


//view inici sessio
//post inici sessio
//get crear activitat
//post store activitat


//Route::get('activitat-form', 'ActivitatController@store');
//https://blastcoding.com/creando-un-formulario-en-laravel/
//https://www.zentica-global.com/es/zentica-blog/ver/como-crear-y-validar-un-formulario-en-laravel-8-6073a87660073