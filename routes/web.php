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

//metode del controlador creat manualment
//Route::post('logged_in', [LoginController::class, 'authenticate']);//login
Auth::routes();#https://linuxhint.com/laravel-new-authroutes/

//el home redirigeix al login si no s'està loguejat
Route::get('/',[ HomeController::class, 'index']);
//HOME
//Route::view('/home','inici');
Route::get('/home', [HomeController::class, 'index'])->name('home');

//JORNADA
//get crear activitat
Route::get('/jornada',[ActivitatController::class,'create'])->name('jornada.form');
//post store activitat
Route::post('/jornada',[ActivitatController::class,'store'])->name('jornada.store');
//tancar jornada
Route::patch('/jornada',[ActivitatController::class,'update'])->name('jornada.update');

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
//check tasques pedidos
Route::get('/pedidos/check',[PedidoController::class,'checkTasques'])->name('check.pedidos');
//check torns
Route::get('/jornada/check',[ActivitatController::class,'checkTorn'])->name('check.torns');
//llistar totes les tasques disponibles
Route::get('/tasques',[PedidoController::class, 'getTasques'])->name('get.tasques');


//https://www.youtube.com/watch?v=eRYz62Cx0Wg&ab_channel=Inform%C3%A1ticaDP