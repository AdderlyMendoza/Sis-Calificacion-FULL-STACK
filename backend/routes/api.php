<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;

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


# Rutas protegidas, necesitan una autenticacion
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // USUARIOS
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'create']);
    Route::put('/users/{id}', [UserController::class, 'update']);

    // POSTULANTES
    Route::get('/postulantes', [PostulanteController::class, 'index']);
    Route::post('/upload-excel', [PostulanteController::class, 'uploadExcel']);

    // DISTRIBUCION DE AULAS
    Route::get('/obtener-campos', [PostulanteController::class, 'obtenerCampos']);
    Route::post('/fr-campos-seleccionados', [PostulanteController::class, 'frCamposSeleccionados']);


    


});
