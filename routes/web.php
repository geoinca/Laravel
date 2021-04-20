<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FileuploadController;
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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/fileupload/create', [FileuploadController::class, 'create'])->name('create_fileupload_path');
Route::get('/fileupload', [FileuploadController::class, 'index'])->name('fileupload_path');

Route::group(['middleware' => 'auth'], function () {

    Route::post('/fileupload', [FileuploadController::class, 'store'])->name('store_fileupload_path');
    Route::post('/fileupload/download', [FileuploadController::class, 'download'])->name('download_fileupload_path');
    Route::post('/fileupload/process', [FileuploadController::class, 'process'])->name('process_fileupload_path');

});

Route::get('job/{filename}',[FileuploadController::class, 'showJobImage'])->name('jobImage');
