<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\LoginController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [MovieController::class, 'index']);
Route::get('/search-movies', [MovieController::class, 'searchMovies']);

Route::get('/guest', [LoginController::class, 'index']);
Route::get('/login-as-guest', [LoginController::class, 'loginAsGuest']);
