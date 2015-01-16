<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showWelcome');

Route::get('domains', array('as'=>'domains', 'uses'=>'DomainsController@index'));
Route::get('domains/{domain_name}', array('as'=>'domain', 'uses'=>'DomainsController@domain'));
Route::get('domains/{domain_name}/{subdomain_name}', array('as'=>'subdomain', 'uses'=>'DomainsController@subdomain'));

Route::resource('notifications', 'NotificationsController');
