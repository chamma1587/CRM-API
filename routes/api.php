<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', ['protected' => true, 'middleware' => 'auth:api'], function ($api) {
    $api->get('crm/customers/{filters}', 'App\Http\Controllers\Crm\CustomerController@getCustomerList');
    $api->get('crm/{customerId}/customer', 'App\Http\Controllers\Crm\CustomerController@getCustomerInfo');
    $api->post('crm/customer', 'App\Http\Controllers\Crm\CustomerController@createCustomer');
    $api->post('crm/customers/import', 'App\Http\Controllers\Crm\CustomerController@importCustomers');
    $api->put('crm/{customerId}/customer', 'App\Http\Controllers\Crm\CustomerController@updateCustomer');
    $api->delete('crm/{customerId}/customer', 'App\Http\Controllers\Crm\CustomerController@deleteCustomer');   
});


$api->version('v1', function ($api) {
    $api->post('auth/login', 'App\Http\Controllers\Auth\AuthController@login');
    $api->post('auth/logout', 'App\Http\Controllers\Auth\AuthController@logout');
});
