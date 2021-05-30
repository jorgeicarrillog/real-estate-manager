<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//autentication tokens
Route::post('/sanctum/token')->name('sanctum.token')->uses('ApiController@token');

Route::group(['prefix' => 'v1', 'as'=>'api.v1.', 'middleware'=>'auth:sanctum'], function()
{
    //propietarios
    Route::get('owners')->name('owners.index')->uses('Api\OwnerApiController@index');
    Route::post('owners')->name('owners.store')->uses('Api\OwnerApiController@store');
    Route::put('owners/{owner}')->name('owners.update')->uses('Api\OwnerApiController@update');
    Route::delete('owners/{owner}')->name('owners.destroy')->uses('Api\OwnerApiController@destroy');

    // Organizations
    Route::get('organizations')->name('organizations')->uses('Api\OrganizationsApiController@index');
    Route::post('organizations')->name('organizations.store')->uses('Api\OrganizationsApiController@store');
    Route::put('organizations/{organization}')->name('organizations.update')->uses('Api\OrganizationsApiController@update');
    Route::delete('organizations/{organization}')->name('organizations.destroy')->uses('Api\OrganizationsApiController@destroy');
    Route::put('organizations/{organization}/restore')->name('organizations.restore')->uses('Api\OrganizationsApiController@restore');
});
