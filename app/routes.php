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

Route::filter('not_auth', function()
{
    if(Auth::check())
    {
      return Redirect::to('/');
    }
});

Route::any('login', ['as'=>'user/login','before'=>'not_auth','uses'=>'UserController@login']);

Route::group(['before' => 'auth'], function()
{
  Route::get('/', 'HomeController@showWelcome');
  Route::any('logout', ['as'=>'user/logout', 'uses'=>'UserController@logout']);

  # Viewing domains
  Route::group(array('prefix' => 'domains'), function()
  {
    Route::get('/', ['as'=>'domains', 'uses'=>'DomainsController@index']);
    Route::get('{domain_name}', ['as'=>'domain', 'uses'=>'DomainsController@domain']);
    Route::get('{domain_name}/{subdomain_name}', ['as'=>'subdomain', 'uses'=>'DomainsController@subdomain']);
  });


  # Notifications
  Route::resource('notifications', 'NotificationsController');

  # Get chart data
  Route::group(array('prefix' => 'charts'), function()
  {
    ## Sum
    Route::group(array('prefix' => 'sum'), function()
    {
      Route::get('bandwidth/{domain_name}/{subdomain_name?}', ['as'=>'chart_sum_bandwidth','uses'=>'SumController@bandwidth']);
      Route::get('storage/{domain_name}/{subdomain_name?}', ['as'=>'chart_sum_storage','uses'=>'SumController@storage']);
    });

    ## Linode
    Route::group(array('prefix' => 'linode'), function()
    {
      Route::get('progress', ['as'=>'linode_progress', 'uses'=>'LinodeController@progress']);
      Route::get('bandwidth', ['as'=>'linode_bandwidth', 'uses'=>'LinodeController@bandwidth']);
      Route::get('storage', ['as'=>'linode_storage', 'uses'=>'LinodeController@storage']);
    });

    ## Bandwidth
    Route::group(array('prefix' => 'over_time'), function()
    {
      Route::get('bandwidth/{domain_name}/{subdomain_name?}', ['as'=>'chart_bandwidth_domain', 'uses'=>'OvertimeController@bandwidth']);
      Route::get('time_taken/{domain_name}/{subdomain_name?}', ['as'=>'chart_time_domain', 'uses'=>'OvertimeController@time_taken']);
      Route::get('month_breakdown', ['as'=>'chart_month_breakdown', 'uses'=>'OvertimeController@month_breakdown']);
      Route::get('top_domains', ['as'=>'chart_top_domains', 'uses'=>'OvertimeController@top_domains']);
    });

    ## Top
    Route::group(['prefix' => 'top'], function()
    {
      Route::get('referers/{domain_name}/{subdomain_name?}', ['as'=>'chart_top_referers', 'uses'=>'TopController@referers']);
      Route::get('pages/{domain_name}/{subdomain_name?}', ['as'=>'chart_top_pages', 'uses'=>'TopController@pages']);
    });

    ## Storage
    Route::group(['prefix' => 'storage'], function()
    {
      Route::get('total/{domain_name}/{subdomain_name}', ['as'=>'chart_storage_total', 'uses'=>'StorageController@total']);
      Route::get('domain/{domain_name}', ['as'=>'chart_storage_domain', 'uses'=>'StorageController@domain']);
    });
  });
});

# User
Route::any('request', ['as'=>'user/request', 'uses'=>'UserController@request']);
Route::any('reset/{token}', ['as'=>'user/reset', 'uses'=>'UserController@reset']);
