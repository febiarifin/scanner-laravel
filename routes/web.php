<?php

use App\Http\Controllers\EventCotroller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QrcodeController;
use Illuminate\Support\Facades\Route;

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

Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'auth'])->name('login.auth')->middleware('guest');

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [EventCotroller::class, 'index']);
    Route::post('scanner/store', [QrcodeController::class, 'post'])->name('scanner.store');

    Route::resource('events', EventCotroller::class);
    Route::post('events/import', [EventCotroller::class, 'import'])->name('events.import');
    Route::get('events/export/{event}', [EventCotroller::class, 'export'])->name('events.export');

    Route::get('/logout', function (){
        \Illuminate\Support\Facades\Session::flush();
        \Illuminate\Support\Facades\Auth::logout();
        return redirect('/');
    })->name('logout');
});
