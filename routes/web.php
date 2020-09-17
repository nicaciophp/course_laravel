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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/model', function () {
//    return \App\User::all();
    return \App\User::find(2);
});


Route::prefix('admin')->namespace('Admin')->group(function (){
    Route::prefix('stores')->group(function (){
        Route::get('/', 'StoreController@index')->name('admin.stores.index');
        Route::get('/create', 'StoreController@create')->name('admin.stores.create');
        Route::post('/store', 'StoreController@store')->name('admin.stores.store');
        Route::get('/{store}/edit', 'StoreController@edit')->name('admin.stores.edit');
        Route::post('/update/{store}', 'StoreController@update')->name('admin.stores.update');
        Route::get('/destroy/store', 'StoreController@destroy')->name('admin.stores.destroy');
    });

});

