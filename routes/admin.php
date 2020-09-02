<?php

use Illuminate\Support\Facades\Route;



Route::view('widget','admin.partials.widget');
Route::middleware(['role:admin','auth'])->prefix('dashboard')->namespace('Admin')->group(function () {
    Route::resource('admin', 'AdminController')->only('index');
    Route::resource('class', 'ClassTableController');
    Route::get('read-data', 'ClassTableController@readData')->name('class.readData');
    Route::resource('guardian', 'GuardianController');
    Route::resource('student', 'StudentController');
});
