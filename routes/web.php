<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TornController;
use App\Http\Controllers\ComandaController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReportsController;

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
/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */
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
Route::get('/jornada',[TornController::class,'create'])->name('jornada.form');
//post store activitat
Route::post('/jornada',[TornController::class,'store'])->name('jornada.store');
//tancar jornada
Route::patch('/jornada',[TornController::class,'update'])->name('jornada.update');

//PEDIDOS
Route::get('/comandes', [ComandaController::class,'create'])->name('comandes.form');
//post store preparacio pedido
Route::post('/comandes',[ComandaController::class,'store'])->name('comandes.store');
//store Revisio pedido
Route::post('/comandes/revisio',[ComandaController::class,'storeRevPedido'])->name('revComandes.store');
//stop pedidos
Route::post('/comandes/stop',[ComandaController::class,'stopPedidos'])->name('stop.pedidos');
//store expedicio pedidos
Route::post('/comandes/expedicio',[ComandaController::class,'storeExpedPedido'])->name('expedComandes.store');
//store saf pedidos
Route::post('/comandes/saf',[ComandaController::class,'storeSAFPedido'])->name('safComandes.store');
//check tasques pedidos
Route::get('/comandes/check',[ComandaController::class,'checkTasques'])->name('check.comandes');
//check torns
Route::get('/jornada/check',[TornController::class,'checkTorn'])->name('check.torns');
//llistar totes les tasques disponibles
Route::get('/tasques',[ComandaController::class, 'getTasques'])->name('get.tasques');


//RECEPCIONS
//Route::get('/recepcions',[]);

//REPORTS ADMIN
Route::get('/reports',[ReportsController::class, 'index'])->name('admin.reports');

Route::get('/set_language/{lang}', [App\Http\Controllers\Controller::class, 'set_language'])->name('set_language');
App::setLocale("es");

//https://www.youtube.com/watch?v=eRYz62Cx0Wg&ab_channel=Inform%C3%A1ticaDP