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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'API','prefix' => 'v1'], function() {
    Route::post('login', ['uses' => 'Auth\LoginController@authenticate']);
    Route::post('', ['uses' => 'Auth\LoginController@authenticate']);

    Route::group(['middleware' => 'jwt.auth'], function() {
        Route::group(['prefix' => 'me'], function() {
            Route::get('/', ['uses' => 'ProfileController@index']);
            Route::get('/menu', ['uses' => 'ProfileController@getMenu']);
            Route::post('/check-permission', ['uses' => 'ProfileController@checkPermission']);
            Route::post('/change-password', ['uses' => 'ProfileController@changePassword']);
            Route::post('/edit-profile', ['uses' => 'ProfileController@editProfile']);
        });
        
        Route::group(['prefix' => 'menu'], function() {
            Route::get('/', ['uses' => 'MenuController@index']);
            Route::post('/store', ['uses' => 'MenuController@store']);
            Route::post('/update', ['uses' => 'MenuController@update']);
            Route::delete('/', ['uses' => 'MenuController@delete']);
        });

        Route::group(['prefix' => 'role'], function() {
            Route::get('/', ['uses' => 'RoleController@index']);
            Route::post('/store', ['uses' => 'RoleController@store']);
            Route::post('/update', ['uses' => 'RoleController@update']);
            Route::delete('/', ['uses' => 'RoleController@delete']);
        });

        Route::group(['prefix' => 'user'], function() {
            Route::get('/', ['uses' => 'UserController@index']);
            Route::post('/store', ['uses' => 'UserController@store']);
            Route::post('/update', ['uses' => 'UserController@update']);
            Route::delete('/', ['uses' => 'UserController@delete']);
        });

        Route::group(['prefix' => 'muzakki'], function() {
            Route::get('/', ['uses' => 'MuzakkiController@index']);
            Route::get('/relation', ['uses' => 'MuzakkiController@relation']);
            Route::get('/export', ['uses' => 'MuzakkiController@export']);
            Route::post('/store', ['uses' => 'MuzakkiController@store']);
            Route::post('/update', ['uses' => 'MuzakkiController@update']);
            Route::delete('/', ['uses' => 'MuzakkiController@delete']);
        });

        Route::group(['prefix' => 'mustahik'], function() {
            Route::get('/', ['uses' => 'MustahikController@index']);
            Route::get('/export', ['uses' => 'MustahikController@export']);
            Route::post('/store', ['uses' => 'MustahikController@store']);
            Route::post('/update', ['uses' => 'MustahikController@update']);
            Route::delete('/', ['uses' => 'MustahikController@delete']);
        });

        Route::group(['prefix' => 'periode'], function() {
            Route::get('/', ['uses' => 'PeriodeController@index']);
            Route::post('/store', ['uses' => 'PeriodeController@store']);
            Route::post('/update', ['uses' => 'PeriodeController@update']);
            Route::get('/status', ['uses' => 'PeriodeController@status']);
        });

        Route::group(['prefix' => 'kualitaszakat'], function() {
            Route::get('/', ['uses' => 'KualitasZakatController@index']);
            Route::get('/type', ['uses' => 'KualitasZakatController@type']);
            Route::post('/store', ['uses' => 'KualitasZakatController@store']);
            Route::post('/update', ['uses' => 'KualitasZakatController@update']);
            Route::delete('/', ['uses' => 'KualitasZakatController@delete']);
        });

        Route::group(['prefix' => 'categorymustahik'], function() {
            Route::get('/', ['uses' => 'CategoryMustahikController@index']);
            Route::post('/store', ['uses' => 'CategoryMustahikController@store']);
            Route::post('/update', ['uses' => 'CategoryMustahikController@update']);
            Route::delete('/', ['uses' => 'CategoryMustahikController@delete']);
        });

        Route::group(['prefix' => 'zakat'], function() {
            Route::get('/', ['uses' => 'ZakatController@index']);
            Route::get('/component', ['uses' => 'ZakatController@component']);
            Route::post('/store', ['uses' => 'ZakatController@store']);
            Route::post('/update', ['uses' => 'ZakatController@update']);
            Route::post('/payment', ['uses' => 'ZakatController@payment']);
            Route::post('/updatestatus', ['uses' => 'ZakatController@updateStatus']);
            Route::delete('/', ['uses' => 'ZakatController@delete']);
            Route::get('/report', ['uses' => 'ZakatController@report']);
            Route::get('/muzakki', ['uses' => 'ZakatController@transaksimuzakki']);
            Route::get('/export', ['uses' => 'ZakatController@export']);
        });

        Route::group(['prefix' => 'hewanqurban'], function() {
            Route::get('/', ['uses' => 'HewanQurbanController@index']);
            Route::post('/store', ['uses' => 'HewanQurbanController@store']);
            Route::post('/update', ['uses' => 'HewanQurbanController@update']);
            Route::delete('/', ['uses' => 'HewanQurbanController@delete']);
        });

        Route::group(['prefix' => 'trxhewanqurban'], function() {
            Route::get('/', ['uses' => 'TrxHewanQurbanController@index']);
            Route::get('/component', ['uses' => 'ZakatController@component']);
            Route::post('/store', ['uses' => 'TrxHewanQurbanController@store']);
            Route::post('/update', ['uses' => 'TrxHewanQurbanController@update']);
            Route::delete('/', ['uses' => 'TrxHewanQurbanController@delete']);
        });

        Route::group(['prefix' => 'qurban'], function() {
            Route::get('/', ['uses' => 'QurbanController@index']);
            Route::get('/component', ['uses' => 'ZakatController@component']);
            Route::post('/store', ['uses' => 'QurbanController@store']);
            Route::post('/update', ['uses' => 'QurbanController@update']);
            Route::delete('/', ['uses' => 'QurbanController@delete']);
        });
    });

    Route::get('/report-dashboard', ['uses' => 'ZakatController@reportDashboard']);
});
