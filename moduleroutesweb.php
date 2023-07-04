<?php
use Illuminate\Support\Facades\Route;
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

Route::group(['middleware' => ['auth']], function () {
    Route::get('patientage', 'ReportController@index')->name('patientage');
    Route::get('datewisedx', 'ReportController@datewisedxindex')->name('datewisedx');
    Route::any('search-by-age', 'ReportController@SearchByAge')->name('search-by-age');
    Route::any('date-wise-dx', 'ReportController@SearchByDate')->name('date-wise-dx');
    Route::group(['prefix' => 'patientage', 'as'=>'patientage.'], function () {
        Route::post('show', 'ReportController@show')->name('show');
    });
});
