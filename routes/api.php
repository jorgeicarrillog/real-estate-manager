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
    Route::get('owners/{owner}')->name('owners.show')->uses('Api\OwnerApiController@show');
    Route::put('owners/{owner}')->name('owners.update')->uses('Api\OwnerApiController@update');
    Route::delete('owners/{owner}')->name('owners.destroy')->uses('Api\OwnerApiController@destroy');

    // Organizations
    Route::get('organizations')->name('organizations')->uses('Api\OrganizationsApiController@index');
    Route::post('organizations')->name('organizations.store')->uses('Api\OrganizationsApiController@store');
    Route::get('organizations/{organization}')->name('organizations.show')->uses('Api\OrganizationsApiController@show');
    Route::put('organizations/{organization}')->name('organizations.update')->uses('Api\OrganizationsApiController@update');
    Route::delete('organizations/{organization}')->name('organizations.destroy')->uses('Api\OrganizationsApiController@destroy');
    Route::put('organizations/{organization}/restore')->name('organizations.restore')->uses('Api\OrganizationsApiController@restore');

    //Properties
    Route::group(['prefix' => 'organizations/{organization}/'], function()
    {
        Route::get('properties')->name('properties.index')->uses('Api\PropertieApiController@index');
        Route::get('properties/create')->name('properties.create')->uses('Api\PropertieApiController@create');
        Route::post('properties')->name('properties.store')->uses('Api\PropertieApiController@store');
        Route::get('properties/{propertie}')->name('properties.show')->uses('Api\PropertieApiController@show');
        Route::get('properties/{propertie}/edit')->name('properties.edit')->uses('Api\PropertieApiController@edit');
        Route::put('properties/{propertie}')->name('properties.update')->uses('Api\PropertieApiController@update');
        Route::delete('properties/{propertie}')->name('properties.destroy')->uses('Api\PropertieApiController@destroy');
        Route::put('properties/{propertie}/restore')->name('properties.restore')->uses('Api\PropertieApiController@restore');
    });
});
