<?php

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

Route::get('/', 'TaskController@index')->name('index');
Route::get('/projects', 'ProjectController@index')->name('project-index');
Route::post('/projects/save', 'ProjectController@save')->name('project-save');
Route::post('/projects/delete', 'ProjectController@delete')->name('project-delete');
Route::post('/projects/restore', 'ProjectController@restore')->name('project-restore');
Route::post('/projects/select', 'ProjectController@select')->name('project-select');

Route::get('/tasks', 'TaskController@getTasks')->name('task-index');
Route::post('/tasks/sort', 'TaskController@sort')->name('task-sort');
Route::post('/tasks/save', 'TaskController@save')->name('task-save');
Route::post('/tasks/delete', 'TaskController@delete')->name('task-delete');
Route::post('/tasks/restore', 'TaskController@restore')->name('task-restore');
