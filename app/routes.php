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

# Viewing domains
Route::group(array('prefix' => 'domains'), function()
{
  Route::get('/', array('as'=>'domains', 'uses'=>'DomainsController@index'));
  Route::get('{domain_name}', array('as'=>'domain', 'uses'=>'DomainsController@domain'));
  Route::get('{domain_name}/{subdomain_name}', array('as'=>'subdomain', 'uses'=>'DomainsController@subdomain'));
});


# Notifications
Route::resource('notifications', 'NotificationsController');

# Get chart data
## Bandwidth

Route::group(array('prefix' => 'charts'), function()
{
  Route::group(array('prefix' => 'bandwidth'), function()
  {
    Route::get('over_time/{domain_name}', array('as'=>'chart_bandwidth_domain', 'uses'=>'BandwidthController@over_time'));
    Route::get('over_time/{domain_name}/{subdomain_name}', array('as'=>'chart_bandwidth_domain', 'uses'=>'BandwidthController@over_time'));
  });
});
