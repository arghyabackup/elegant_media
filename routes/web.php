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

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', 'HomeController@index')->name('home');
Route::get('/entry-ticket', 'HomeController@entry_ticket')->name('home.entryTicket');
Route::post('/submit-ticket', 'HomeController@submit_ticket')->name('home.submitTicket');
Route::post('/view-ticket', 'HomeController@view_ticket')->name('home.viewTicket');

Route::group(['prefix' => 'support'], function() {
    Route::get('/', 'SupportController@index')->name('support');
    Route::get('/view/{id}', 'SupportController@view')->name('support.view');
    Route::post('/reply', 'SupportController@reply')->name('support.reply');
    Route::get('/registration', 'SupportController@registration')->name('support.registration');
    Route::post('/post-registration', 'SupportController@postRegistration')->name('support.postRegistration');
    Route::get('/login', 'SupportController@login')->name('support.login');
    Route::post('/post-login', 'SupportController@post_login')->name('support.postLogin');
    Route::get('/logout', 'SupportController@logout')->name('support.logout');
});