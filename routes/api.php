<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* metode del controlador creat manualment */
Route::post('logged_in', [App\Http\Controllers\Auth\LoginController::class, 'authenticate']);
Auth::routes();

//el home redirigeix al login si no s'està loguejat
Route::get('/',[ HomeController::class, 'index']);
//HOME
Route::view('/home','inici');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//JORNADA
//get crear activitat
Route::get('/activitat',[ActivitatController::class,'create'])->name('jornada.form');
//post store activitat
Route::post('/home',[ActivitatController::class,'store'])->name('jornada.store');
//tancar jornada
Route::patch('/activitat',[ActivitatController::class,'update'])->name('jornada.update');

//PEDIDOS
Route::get('/pedidos', [PedidoController::class,'create'])->name('pedidos.form');
//post store preparacio pedido
Route::post('/pedidos',[PedidoController::class,'store'])->name('pedidos.store');
//store Revisio pedido
Route::post('/pedidos/revisio',[PedidoController::class,'storeRevPedido'])->name('revPedidos.store');
//stop pedidos
Route::post('/pedidos/stop',[PedidoController::class,'stopPedidos'])->name('stop.pedidos');
//store expedicio pedidos
Route::post('/pedidos/expedicio',[PedidoController::class,'storeExpedPedido'])->name('expedPedidos.store');
//store saf pedidos
Route::post('/pedidos/saf',[PedidoController::class,'storeSAFPedido'])->name('safPedidos.store');