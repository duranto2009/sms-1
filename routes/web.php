<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$routes = [
    'admin'
];

foreach ($routes as $route) {
    require(__DIR__ . '/' . $route . '.php');
}

Route::get('/', function () {
    return view('index');
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

