<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/projects', 'App\Http\Controllers\ProjectsController@index');
    Route::get('/projects/create', 'App\Http\Controllers\ProjectsController@create');
    Route::get('/projects/{project}', 'App\Http\Controllers\ProjectsController@show');
    Route::post('/projects', 'App\Http\Controllers\ProjectsController@store');


    Route::post('/projects/{project}/tasks', 'App\Http\Controllers\ProjectTasksController@store');
    Route::patch('/projects/{project}/tasks/{task}', 'App\Http\Controllers\ProjectTasksController@update');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
