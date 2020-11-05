<?php

use Illuminate\Support\Facades\Route;
use RealRashid\SweetAlert\Facades\Alert;
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

    return redirect('/home');
});


//routes that require login
Auth::routes();
Route::get('/home', 'CryptoController@index');
Route::get('/crypto-filter', 'CryptoController@CryptoFilter');
Route::group(['middleware' => 'auth'], function () {

//timeline routes and possibility to rate crypto's

Route::post('/home', 'RatingController@create');
route::get('crypto-search', 'CryptoController@CryptoSearch');
//filtering and searching for cryptos

Route::post('/crypto-filter', 'RatingController@create');
route::post('crypto-search', 'RatingController@create');

//view the users own crypto's
route::get('cryptos/own', 'CryptoController@UserCrypto' )->middleware('role:author,admin');
Route::post('cryptos/own', 'RatingController@create');
//show other users crypto's
Route::get('cryptos/other/{user}', 'CryptoController@otherCrypto');
Route::post('cryptos/other/{user}', 'RatingController@create');
//admin page to review crypto's and make them visible to other users
Route::get('cryptos/review', 'CryptoController@review')->middleware('role:admin');
Route::patch('cryptos/review', 'CryptoController@visibility')->middleware('role:admin');

//CRUD of Cryptos allowed by admins and authors
Route::get('cryptos/create', 'ClassificationController@load')->middleware('role:author,admin');
Route::post('cryptos/create', 'CryptoController@store')->middleware('role:author,admin');
Route::get('cryptos/{crypto}', 'CryptoController@show');
Route::get('cryptos/{crypto}/edit', 'CryptoController@edit')->middleware('role:author,admin');;
Route::patch('cryptos/{crypto}/', 'CryptoController@update')->middleware('role:author,admin');;
Route::get('cryptos/{crypto}/delete', 'CryptoController@delete')->middleware('role:author,admin');;

//show all users and edit them only for admin
route::get('users/all', 'UserController@index')->middleware('role:admin');
route::get('users/{user}/edit', 'UserController@edit')->middleware('role:admin');
route::patch('users/{user}/', 'UserController@update')->middleware('role:admin');

//create classification only for admin
Route::get('classifications/create', 'ClassificationController@create')->middleware('role:admin');
Route::post('classifications/create', 'ClassificationController@store')->middleware('role:admin');

Route::get('cryptos/coingecko', 'CryptoController@loadCoingecko')->middleware('role:admin');

});
