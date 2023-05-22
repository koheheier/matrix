<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/home', \App\Http\Controllers\HomeController::class)->name('home');
});


Route::get('/first_routing/{target}/{arg}', \App\Http\Controllers\firstRouteController::class)
->name('first_routing')
->where('matrixId', '[0-9]+')
;

Route::get('/make_matrix', \App\Http\Controllers\makeMatrixController::class)->name('make_matrix');
Route::get('/list', [\App\Http\Controllers\showController::class, 'list'])->name('list');
Route::get('/make', [\App\Http\Controllers\updateController::class, 'make']);
Route::post('/create_matrix', \App\Http\Controllers\createMatrixController::class)->name('create_matrix');

Route::get('/make_candidates/{matrixId}', \App\Http\Controllers\makeCandidatesController::class)->name('make_candidates')->where('matrixId', '[0-9]+');
Route::post('/create_candidates', \App\Http\Controllers\createCandidatesController::class)->name('create_candidates');

Route::get('/update/matrix_data/{matrixId}', \App\Http\Controllers\Update\matrixDataController::class)->name('update_matrix_data')->where('matrixId', '[0-9]+');
Route::get('/update/candidates_data/{matrixId}', [\App\Http\Controllers\Update\updateCandidatesController::class, 'update'])
    ->name('update_candidates')->where('matrixId', '[0-9]+');

Route::get('/ranking/{matrixId}', \App\Http\Controllers\rankingController::class)->name('ranking')->where('matrixId', '[0-9]+');

Route::post('/update/put/', \App\Http\Controllers\Update\putController::class)->name('update.put');




require __DIR__.'/auth.php';
