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
//use Mail;

Route::group(['middleware' => ['speed']], function() {
    Route::get('/', 'HomeController@index');
    Route::get('/check-zakat', 'HomeController@checkZakat');
    Route::post('/check-zakat', 'HomeController@doCheckZakat');
    Route::get('/report-zakat', 'HomeController@reportZakat');
    Route::get('print', ['uses' => '\App\Http\Controllers\API\ZakatController@doPrint']);
    Route::get('print-qurban', ['uses' => '\App\Http\Controllers\API\QurbanController@doPrint']);
    Route::get('muzakki/export', ['uses' => '\App\Http\Controllers\API\MuzakkiController@export']);
    Route::get('mustahik/export', ['uses' => '\App\Http\Controllers\API\MustahikController@export']);
    Route::get('zakat/export', ['uses' => '\App\Http\Controllers\API\ZakatController@export']);
    Route::get('zakat/mail', ['uses' => '\App\Http\Controllers\API\ZakatController@sendmail']);
    // Route::get('send/notif', function(){
    //     Mail::send('mails.receipt', [], function($message) {
    //         $message->to("fariz.ariyanu@gmail.com", "Faris Ariyanu")
    //             ->subject("Tanda Terima Zakat");
    //     });
    // });
});

Route::get('/admin/{vue_capture?}', 'AppController@index')
    ->middleware(['speed'])
    ->where('vue_capture', '[\/\w\.\,\-]*');


