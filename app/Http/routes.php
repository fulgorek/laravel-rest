<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// main site
Route::get('/', function () {
    return view('welcome');
});

// group our endpoints under api/v1
Route::group(['prefix' => 'api/v1'], function() {

  // We need only post/authenticate to retrieve our token
  Route::post('authenticate', 'AuthenticateController@authenticate');

  // list of allowed methods
  Route::resource('names', 'NameController', [
    'parameters' => ['names' => 'id'],
    'only' => [
      'index', 'store', 'show', 'update', 'destroy'
    ]]);
});
