<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivitatController;

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


//view inici sessio
//post inici sessio
//get crear activitat
//post store activitat

/**enllaços */
/*
https://www.oulub.com/es-ES/Laravel/authentication
https://codea.app/cursos/laravel-pagina-web-administrable/login-laravel-100
*/


//https://blastcoding.com/creando-un-formulario-en-laravel/
//https://www.zentica-global.com/es/zentica-blog/ver/como-crear-y-validar-un-formulario-en-laravel-8-6073a87660073
Auth::routes();

/*HOME*/
Route::view('/home','inici');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/*FORMULARI*/
Route::get('/home/activitat',[App\Http\Controllers\ActivitatController::class,'create'])->name('activitat-form.store');
Route::post('/home/activitat',[App\Http\Controllers\ActivitatController::class,'store'])->name('home.store');

/*create task form*/
//Route::get('projecte/tasques/crear',[TaskController::class, 'create'])->name('task.create');

/*save task*/
//Route::post('projecte/tasques/crear',[TaskController::class,'store'])->name('tasks.store');//receives form info