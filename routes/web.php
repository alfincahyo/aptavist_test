<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\MatchesController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StandingsController;
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

Route::get('/', [StandingsController::class, 'index']);


Route::resource('clubs', ClubController::class);

Route::get('/standings', [StandingsController::class, 'index'])->name('standings.index');
Route::post('matches/post-multiple-matches', [MatchesController::class, 'store_multiple'])->name('matches.post-multiple');
Route::get('matches/add-multiple-matches', [MatchesController::class, 'create_multiple'])->name('matches.create-multiple');
Route::post('matches/post-single-matches', [MatchesController::class, 'store_single'])->name('matches.post-single');
Route::get('matches/add-single-matches', [MatchesController::class, 'create_single'])->name('matches.create-single');
Route::resource('matches', MatchesController::class);

Route::get('search/clubs', [SearchController::class, 'clubs'])->name('search.clubs');
