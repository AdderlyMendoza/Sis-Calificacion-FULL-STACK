<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\FichasIdentificacionController;
use App\Http\Controllers\FichasRespuestasController;
use App\Http\Controllers\RespuestasCorrectasController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\ProcesoAdmisionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

#Prueba de API
Route::get("/test", function (Request $request) {
    return response()->json(["API" => "Its working!!!"]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::post('/users', [UserController::class, 'create']);
// Route::get('/users', [UserController::class, 'index']);

// Route::post('/fr-id-seleccionados', [CalificacionController::class, 'frIdSeleccionados']);


# Rutas protegidas, necesitan una autenticacion
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // USUARIOS
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'create']);
    Route::put('/users/{id}', [UserController::class, 'update']);

    // #################################################  PROCESOS DE ADMISION  ################################################
    Route::get('/procesosAdmision', [ProcesoAdmisionController::class, 'index']);



    // ################################################  DISTRIBUCION DE AULAS  ################################################
    // Postulantes
    Route::get('/postulantes', [PostulanteController::class, 'index']);
    Route::post('/upload-excel', [PostulanteController::class, 'uploadExcel']);

    // Distribucion de aulas
    Route::get('/obtener-campos', [PostulanteController::class, 'obtenerCampos']);
    Route::post('/fr-campos-seleccionados', [PostulanteController::class, 'frCamposSeleccionados']);

    // #####################################################  CALIFICACION  #####################################################
    // Ficha identificacion 
    Route::post('/fr-id-postulantes', [FichasIdentificacionController::class, 'frIdPostulantes']);
    Route::get('/datosFichaIdentificacion', [FichasIdentificacionController::class, 'datosIdentificacion']);
    Route::get('/listarFichasIdentificacion', [FichasIdentificacionController::class, 'listarArchivos']);

    // Respuestas correctas
    Route::post('/fr-resp-correctas', [RespuestasCorrectasController::class, 'frRespCorrectas']);
    Route::get('/datosFichaRespuestasCorrectas', [RespuestasCorrectasController::class, 'datosRespuestas']);
    Route::get('/listarFichasRespuestasCorrectas', [RespuestasCorrectasController::class, 'listarArchivos']);


    // Ficha respuestas
    Route::post('/fr-resp-postulantes', [FichasRespuestasController::class, 'frRespPostulantes']);
    Route::get('/datosFichaRespuestas', [FichasRespuestasController::class, 'datosRespuestas']);
    Route::get('/listarFichasRespuestas', [FichasRespuestasController::class, 'listarArchivos']);
    Route::post('/fr-out-resultados/{tipo}', [CalificacionController::class, 'exportarResultados']);
});
