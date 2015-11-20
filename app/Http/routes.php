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

//Route::get('/{name}', 'TaskController@index')->where('name', '(home|tasks)?');

Route::get('/{name}', [
    'middleware' => 'my.auth',
    'uses' => 'TaskController@index',
])->where('name', '(home|tasks)?');

//Route::post('/task', 'TaskController@store');
Route::post('task', [
    'middleware' => 'my.auth',
    'uses' => 'TaskController@store',
]);

//Route::delete('/task/{task}', 'TaskController@destroy');
Route::delete('/task/{task}', [
    'middleware' => 'my.auth',
    'uses' => 'TaskController@destroy',
]);

//Route::get('/user/{id}', 'UserController@show');
//Route::post('auth/login', 'UserController@postLogin');

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration Routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');


/*
Route::get('/{name}', function () {
    return view('welcome');
})->where('name', '(home)?');
*/
