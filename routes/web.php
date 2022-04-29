<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TornController;
use App\Http\Controllers\ComandaController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RecepcioController;
use App\Http\Controllers\ReoperacionsController;
use App\Http\Controllers\InventariController;

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
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth:sanctum','verified']], function() {
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
//store expedicio pedidos
Route::post('/comandes/expedicio',[ComandaController::class,'storeExpedPedido'])->name('expedComandes.store');
//store saf pedidos
Route::post('/comandes/saf',[ComandaController::class,'storeSAFPedido'])->name('safComandes.store');
//check tasques pedidos
Route::get('/comandes/check',[ComandaController::class,'checkTasques'])->name('check.comandes');
//check torns
Route::get('/jornada/check',[TornController::class,'checkTorn'])->name('check.torns');

//RECEPCIONS
Route::get('/recepcions',[RecepcioController::class,'index'])->name('recepcions.form');
Route::post('/recepcio/descarga',[RecepcioController::class,'storeDescarga'])->name('recepcions.descarga');
Route::post('/recepcio/entrada',[RecepcioController::class,'storeEntrada'])->name('recepcions.entrada');
Route::post('/recepcio/calidad',[RecepcioController::class,'storeControlCalidad'])->name('recepcions.calidad');
Route::post('/recepcio/ubicar',[RecepcioController::class,'storeUbicarProducto'])->name('recepcions.ubicar');

//REOPERACIONS
Route::get('/reoperacions',[ReoperacionsController::class,'index'])->name('reoperacions.form');
Route::post('/reoperacions/lectura',[ReoperacionsController::class,'storeLecturaProd'])->name('reoperacions.prod');
Route::post('/reoperacions/embolsar',[ReoperacionsController::class,'storeEmbolsar'])->name('reoperacions.embolsar');
Route::post('/reoperacions/etiquetar',[ReoperacionsController::class,'storeEtiquetar'])->name('reoperacions.etiq');
Route::post('/reoperacions/otros',[ReoperacionsController::class,'storeOtros'])->name('reoperacions.otros');

//INVENTARI
Route::get('/inventari',[InventariController::class,'index'])->name('inventari.form');
Route::post('/inventari/compactar',[InventariController::class,'storeCompactar'])->name('inventari.compact');
Route::post('/inventari/inventariar',[InventariController::class,'storeInventariar'])->name('inventari.inv');

//REPORTS ADMIN
Route::get('/reports',[ReportsController::class, 'index'])->name('admin.reports');
/* json */
Route::get('/reporting',[ReportsController::class, 'show'])->name('reporting');
Route::post('/consulta',[ReportsController::class, 'twoDateQuery'])->name('admin.query');
Route::post('/consulta-completa',[ReportsController::class, 'completeQuery'])->name('admin.complete.query');
Route::get('/employees-query',[ReportsController::class, 'getEmployees'])->name('admin.getEmployees');
Route::post('/consulta-turno',[ReportsController::class, 'shiftQuery'])->name('admin.shiftQuery');
Route::post('/consulta-activitat',[ReportsController::class, 'taskQuery'])->name('admin.taskQuery');
});

//GESTIO ADMIN USERS
Route::get('/usuarios',[ReportsController::class, 'indexUsers'])->name('admin.users');
//edit user form
Route::get('/usuarios/edit/{usuari}', [ReportsController::class, 'editUser'])->name('user.edit');
//update user
Route::put('/updateUser/{usuari}',[ReportsController::class, 'updateUser'])->name('user.update');
//show all users
Route::get('/userslist',[ReportsController::class, 'showUsers'])->name('show.users');
//create user
Route::post('/createUser',[ReportsController::class, 'createUser'])->name('create.user');
//delete user
Route::delete('/deleteUser/{user}',[ReportsController::class, 'deleteUser'])->name('delete.user');

//language
Route::get('/set_language/{lang}', [App\Http\Controllers\Controller::class, 'set_language'])->name('set_language');
App::setLocale("es");
