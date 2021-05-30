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

// Auth
Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest');
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

// Dashboard
Route::get('/')->name('dashboard')->uses('DashboardController')->middleware('auth');

// Users
Route::get('users')->name('users')->uses('UsersController@index')->middleware('remember', 'auth');
Route::get('users/create')->name('users.create')->uses('UsersController@create')->middleware('auth');
Route::post('users')->name('users.store')->uses('UsersController@store')->middleware('auth');
Route::get('users/{user}/edit')->name('users.edit')->uses('UsersController@edit')->middleware('auth');
Route::put('users/{user}')->name('users.update')->uses('UsersController@update')->middleware('auth');
Route::delete('users/{user}')->name('users.destroy')->uses('UsersController@destroy')->middleware('auth');
Route::put('users/{user}/restore')->name('users.restore')->uses('UsersController@restore')->middleware('auth');

// Images
Route::get('/img/{path}', 'ImagesController@show')->where('path', '.*');

// Organizations
Route::get('organizations')->name('organizations')->uses('OrganizationsController@index')->middleware('remember', 'auth');
Route::get('organizations/create')->name('organizations.create')->uses('OrganizationsController@create')->middleware('auth');
Route::post('organizations')->name('organizations.store')->uses('OrganizationsController@store')->middleware('auth');
Route::get('organizations/{organization}/edit')->name('organizations.edit')->uses('OrganizationsController@edit')->middleware('auth');
Route::put('organizations/{organization}')->name('organizations.update')->uses('OrganizationsController@update')->middleware('auth');
Route::delete('organizations/{organization}')->name('organizations.destroy')->uses('OrganizationsController@destroy')->middleware('auth');
Route::put('organizations/{organization}/restore')->name('organizations.restore')->uses('OrganizationsController@restore')->middleware('auth');

// Reports
Route::get('reports')->name('reports')->uses('ReportsController')->middleware('auth');


//Owners
Route::get('owners')->name('owners')->uses('OwnerController@index')->middleware('remember', 'auth');
Route::get('owners/create')->name('owners.create')->uses('OwnerController@create')->middleware('auth');
Route::post('owners')->name('owners.store')->uses('OwnerController@store')->middleware('auth');
Route::get('owners/{owner}/edit')->name('owners.edit')->uses('OwnerController@edit')->middleware('auth');
Route::put('owners/{owner}')->name('owners.update')->uses('OwnerController@update')->middleware('auth');
Route::delete('owners/{owner}')->name('owners.destroy')->uses('OwnerController@destroy')->middleware('auth');

//web selects
Route::group(['prefix' => 'web-api', 'as'=>'web-api.'], function()
{
    Route::get('countries')->name('countries')->uses('ApiController@countries');
    Route::get('departments')->name('departments')->uses('ApiController@departments');
    Route::get('cities')->name('cities')->uses('ApiController@cities');
});

// 500 error
Route::get('500', function () {
    echo $fail;
});
