<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ActivitatController;
use App\Http\Controllers\PedidoController;

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

Route::post('logged_in', [LoginController::class, 'authenticate']);
Auth::routes();

/* el home redirigeix al login si no s'està loguejat */
Route::get('/',[ HomeController::class, 'index']);
/*HOME*/
Route::view('/home','inici');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*JORNADA*/
//get crear activitat
Route::get('/activitat',[ActivitatController::class,'create'])->name('jornada.form');
//post store activitat
Route::post('/home',[ActivitatController::class,'store'])->name('jornada.store');

Route::patch('/activitat',[ActivitatController::class,'update'])->name('jornada.update');

/* PEDIDOS */
Route::get('/pedidos', [PedidoController::class,'create'])->name('pedidos.form');
//post store preparacio pedido
Route::post('/pedidos',[PedidoController::class,'store'])->name('pedidos.store');
//store Revisio pedido
Route::post('/pedidos/revisio',[PedidoController::class,'storeRevPedido'])->name('revPedidos.store');
//stop pedidos
Route::post('/pedidos/stop',[PedidoController::class,'stopPedidos'])->name('stop.pedidos');
//check pedidos
//Route::post('/pedidos/check',[PedidoController::class,'stopPedidos'])->name('check.tasques');





/* Route::get('/', function () {

    return view('welcome');
    
}); */

/* Route::group(['middleware' => 'auth'], function(){


}); */



/* Route::get('/home/activitat/{variable}', function ($variable) {
    return $variable;
}); */

/* Route::view('/inici-form', 'inici-form'); */
/**enllaços */
/*
https://www.oulub.com/es-ES/Laravel/authentication
*/