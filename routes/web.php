<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->post('register', 'AuthController@register');
$router->post('login', 'AuthController@login');
$router->post('/organization/store', 'OrganizationController@store');
$router->post('/volunteer/store', 'VolunteerController@store');
$router->get('/tags/list', 'TagController@list');
$router->get('/cities/list', 'CityController@list');

$router->group(['middleware' => 'auth'], function () use ($router) {

    $router->get('/organization/show/{id}', 'OrganizationController@show');
    $router->get('/organization/list', 'OrganizationController@list');
    $router->get('/organization/activejobs', 'OrganizationController@activeJobs');
    $router->get('/organization/alljobs', 'OrganizationController@allJobs');
    $router->post('/organization/updaterequest', 'OrganizationController@updateRequest');
    $router->post('/organization/ratevolunteer', 'OrganizationController@rateVolunteer');
    $router->post('/organization/update', 'OrganizationController@update');


    $router->get('/volunteer/show/{id}', 'VolunteerController@show');
    $router->get('/volunteer/list', 'VolunteerController@list');
    $router->post('/volunteer/request', 'VolunteerController@request');
    $router->post('/volunteer/update', 'VolunteerController@update');


    $router->post('/jobs/jobsbytags', 'JobController@getJobByTags');
    $router->post('/jobs/store', 'JobController@store');
    $router->post('/jobs/requests', 'JobController@requests');
    $router->get('/jobs/show/{id}', 'JobController@show');
    $router->post('/jobs/volunteers', 'JobController@volunteers');

    $router->post('/tags/store', 'TagController@store');


    $router->get('/userdata','AuthController@userData');

});
