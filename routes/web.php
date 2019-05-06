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
Route::group(['middleware' => ['auth']], function(Router $router){
    $router->get('/', 'HomeController@index')->name('home');

    $router->group(['prefix' => 'register', 'namespace' => 'Auth'], function(Router $router){
        $router->get('/', 'RegisterController@showRegistrationForm')->name('register');
        $router->post('/', 'RegisterController@registerUser');
    });

    $router->group(['prefix' => 'settings', 'namespace' => 'Settings', 'as' => 'settings.'], function(Router $router){
	    $router->get('/category-properties/{category?}', 'CategoryPropertiesController@index')->name('category_properties');
        $router->post('/category-properties/{category}', 'CategoryPropertiesController@associate')->name('category_properties.associate');
    });
});