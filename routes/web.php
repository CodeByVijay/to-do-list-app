<?php

use App\Http\Controllers\ToDoListController;
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
Route::get('all-task',[ToDoListController::class,'getAllTask'])->name('getAllList');
Route::get('get-not-complete-task',[ToDoListController::class,'getNotCompleteTask'])->name('getNotCompleteTask');
Route::post('done-task',[ToDoListController::class,'doneTask'])->name('doneTask');
Route::post('remove-done-task',[ToDoListController::class,'removeDoneTask'])->name('removeDoneTask');
Route::post('check-task',[ToDoListController::class,'checkTask'])->name('checkTask');
Route::post('add-task',[ToDoListController::class,'addTask'])->name('addTask');
Route::post('delete-task',[ToDoListController::class,'deleteTask'])->name('deleteTask');

