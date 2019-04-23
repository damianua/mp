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

use Illuminate\Routing\Router;

Auth::routes(['register' => false]);
Route::group(['middleware' => ['auth']], function(){
    Route::get('/', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'register', 'namespace' => 'Auth'], function(Router $router){
        $router->get('/', 'RegisterController@showRegistrationForm')->name('register');
        $router->post('/', 'RegisterController@registerUser');
    });
});