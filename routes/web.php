<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UsersController;
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


Route::group(['middleware'=> ['auth']], function(){

    Route::group(['prefix'=> 'admin', 'as'=> 'admin', 'namespace'=> 'Admin'], function(){

        //users
        Route::get('/users', [UsersController::class, 'index'])->name('users.all');
        Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::post('/users/edit/{id}', [UsersController::class, 'edit'])->name('users.edit');
        Route::get('/users/delete/{id}', [UsersController::class, 'delete'])->name('users.delete');
        Route::post('/users/create', [UsersController::class, 'store'])->name('users.store');

        //tasks
        Route::get('/tasks', [TaskController::class, 'index'])->name('task.all');
        Route::get('/task/{id}', [TaskController::class, 'show'])->name('task.show');
        Route::post('/task/edit/{id}', [TaskController::class, 'edit'])->name('task.edit');
        Route::get('/task/delete/{id}', [TaskController::class, 'delete'])->name('task.delete');
        Route::post('/task/create', [TaskController::class, 'store'])->name('task.store');
    });

});