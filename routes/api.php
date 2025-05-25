<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\loginController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\PlanController;
use App\Http\Controllers\api\CelularController;
use App\Http\Controllers\api\ClienteController;
use App\Http\Controllers\api\SedeController;
use App\Http\Controllers\api\SedeVendedorController;
use App\Http\Controllers\api\FijoController;
use App\Http\Controllers\api\MovilController;
use App\Http\Controllers\api\GrupoController;
use App\Http\Controllers\api\MensajeController;
use App\Http\Controllers\api\EstadisticasController;

Route::post('/login', [loginController::class, 'login']);
//Route::post('/register', [UserController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [loginController::class, 'logout']);

    Route::get('/me', [UserController::class, 'me']);
    Route::get('/me/grupos', [GrupoController::class, 'misGrupos']);

    Route::get('/planes', [PlanController::class, 'index']);
    Route::get('/planes/{id}', [PlanController::class, 'show']);
    Route::get('/codigos', [PlanController::class, 'getAllCodigos']);

    Route::get('/celulares', [CelularController::class, 'index']);
    Route::get('/celulares/{id}', [CelularController::class, 'show']);
    Route::put('/celulares/{id}', [CelularController::class, 'update']);
    Route::get('/marcas', [CelularController::class, 'getAllMarcas']);
    Route::get('/modelos/{marca}', [CelularController::class, 'getModelosByMarca']);

    Route::prefix('estadisticas')->group(function () {
        Route::get('/', [EstadisticasController::class, 'getStatistics']);
        Route::get('/fijos', [EstadisticasController::class, 'getFijosStatistics']);
        Route::get('/moviles', [EstadisticasController::class, 'getMovilesStatistics']);
        Route::get('/ventas-mes-actual', [EstadisticasController::class, 'VentasPorMesAnioActual']);
        Route::get('/mejor-vendedor', [EstadisticasController::class, 'MejorVendedorGeneral']);
    });


    Route::resource('users', UserController::class)->only(['index', 'show', 'update']);
    Route::resource('clientes', ClienteController::class)->only(['index', 'show', 'update', 'store']);
    Route::resource('sedes', SedeController::class)->only(['index', 'show']);
    Route::resource('fijos', FijoController::class);

    Route::resource('moviles', MovilController::class);

    Route::resource('grupos', GrupoController::class);
    Route::get('/grupos/{id}/users', [GrupoController::class, 'usuariosGrupo']);
    Route::post('/grupos/{id}/users', [GrupoController::class, 'agregarUsuario']);
    Route::delete('/grupos/{id}/users/{userId}', [GrupoController::class, 'eliminarUsuario']);
    Route::patch('/grupos/{id}/users/{userId}', [GrupoController::class, 'cambiarRol']);
    Route::get('/grupos/{grupoId}/mensajes', [MensajeController::class, 'index']);
    Route::post('/grupos/{grupoId}/mensajes', [MensajeController::class, 'store']);
    Route::get('/grupos/{grupoId}/mensajes/{mensajeId}', [MensajeController::class, 'show']);

    Route::get('/export/moviles', [MovilController::class, 'export'])->name('moviles.export');
    Route::get('/export/fijos', [FijoController::class, 'export'])->name('fijos.export');
});

Route::middleware(['auth:sanctum', 'role:admin,administrador'])->group(function () {
    Route::resource('users', UserController::class)->only(['store', 'destroy']);

    Route::delete('/clientes/{id}', [ClienteController::class, 'destroy']);

    Route::post('/planes', [PlanController::class, 'store']);
    Route::delete('/planes/{id}', [PlanController::class, 'destroy']);
    Route::patch('/planes/{id}', [PlanController::class, 'update']);
    Route::post('/planes/batch', [PlanController::class, 'storeMultiple']);

    Route::patch('/celulares/{id}', [CelularController::class, 'update']);
    Route::post('/celulares', [CelularController::class, 'store']);
    Route::delete('/celulares/{id}', [CelularController::class, 'destroy']);
    Route::post('/celulares/batch', [CelularController::class, 'storeMultiple']);

    Route::resource('sedes', SedeController::class)->only(['store', 'update', 'destroy']);
    Route::resource('sedes-vendedores', SedeVendedorController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
});