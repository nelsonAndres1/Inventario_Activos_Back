<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiAuthMiddleware;

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


Route::post('/api/register',[App\Http\Controllers\Gener02Controller::class,'register']);
Route::post('/api/login',[App\Http\Controllers\Gener02Controller::class,'login']);
Route::post('/api/user/update',[App\Http\Controllers\Gener02Controller::class,'update']);
Route::post('/api/user/findGener02',[App\Http\Controllers\Gener02Controller::class,'findGener02']);
Route::post('/api/user/permisos',[App\Http\Controllers\Gener02Controller::class,'permisos']);



Route::post('/api/user/getConta19',[App\Http\Controllers\Conta19Controller::class,'getConta19']);
Route::get('/api/conta19/buscar',[App\Http\Controllers\Conta19Controller::class,'searchConta19']);
Route::post('/api/user/saveConta124',[App\Http\Controllers\Conta19Controller::class,'saveConta124']);
Route::post('/api/conta19/getCedTra',[App\Http\Controllers\Conta19Controller::class,'getCedTra']);
Route::post('/api/conta19/saveConta123',[App\Http\Controllers\Conta19Controller::class,'saveConta123']);
Route::post('/api/conta19/ConsulConta19',[App\Http\Controllers\Conta19Controller::class,'ConsulConta19']);
Route::post('/api/conta19/SaveConta65',[App\Http\Controllers\Conta19Controller::class,'SaveConta65']);
Route::post('/api/conta19/getDocumentoConta65',[App\Http\Controllers\Conta19Controller::class,'getDocumentoConta65']);
Route::post('/api/conta19/updateConta19',[App\Http\Controllers\Conta19Controller::class,'updateConta19']);
Route::post('/api/conta19/getConta19A',[App\Http\Controllers\Conta19Controller::class,'getConta19A']);
Route::post('/api/conta19/preguntarContinuarInventario',[App\Http\Controllers\Conta19Controller::class,'preguntarContinuarInventario']);



Route::post('/api/conta19/ConsultaR',[App\Http\Controllers\Conta19Controller::class,'ConsultaR']);



Route::post('/api/reporte/reporte',[App\Http\Controllers\ReporteController::class,'reporte']);
Route::get('/api/reporte/searchGener02',[App\Http\Controllers\ReporteController::class,'searchGener02']);
Route::post('/api/reporte/reporte2',[App\Http\Controllers\ReporteController::class,'reporte2']);
Route::post('/api/reporte/reporteH2',[App\Http\Controllers\ReporteController::class,'reporteH2']);

Route::post('/api/traslado/SaveConta65',[App\Http\Controllers\TrasladoController::class,'SaveConta65']);
Route::post('/api/traslado/updateConta19',[App\Http\Controllers\TrasladoController::class,'updateConta19']);
Route::post('/api/traslado/getConta116',[App\Http\Controllers\TrasladoController::class,'getConta116']);


Route::get('/api/formulario/searchGener02',[App\Http\Controllers\FormularioController::class,'searchGener02']);

Route::get('/api/conta19/traer_nombre',[App\Http\Controllers\Conta19Controller::class,'traer_nombre']);


Route::post('/api/conta148/saveConta148',[App\Http\Controllers\Conta148Controller::class,'saveConta148']);

Route::post('/api/conta148/getConta148',[App\Http\Controllers\Conta148Controller::class,'getConta148']);

Route::post('/api/conta148/getConta148_C',[App\Http\Controllers\Conta148Controller::class,'getConta148_C']);

Route::post('/api/conta148/Cerrar_Periodo',[App\Http\Controllers\Conta148Controller::class,'Cerrar_Periodo']);

Route::post('/api/reporte/searchGener02_sub',[App\Http\Controllers\ReporteController::class,'searchGener02_sub']);


Route::post('/api/reporte/reporte_general',[App\Http\Controllers\ReporteController::class,'reporte_general']);

