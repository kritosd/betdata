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

Auth::routes();

/**
 * Groups of routes that needs authentication to access.
 */

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'EventController@datatables')->name('events.datatables');
    Route::get('/events/{sportId}', 'EventController@json');
    // Route::get('/eventsBySportId/{sportId}', 'EventController@jsonBySportId');

    Route::get('/groups/{sportId}', 'GroupController@json');
    Route::post('/group/{groupId}', 'GroupController@update');
    Route::get('/sports', 'SportController@json');
});