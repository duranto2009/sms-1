<?php

use Illuminate\Support\Facades\Route;



Route::view('widget','admin.partials.widget');
Route::middleware(['role:admin','auth'])->prefix('dashboard')->namespace('Admin')->group(function () {
    Route::resource('admin', 'AdminController')->only('index');
    Route::resource('class', 'ClassTableController');
    Route::get('read-data', 'ClassTableController@readData')->name('class.readData');
    Route::resource('guardian', 'GuardianController');
    Route::get('read-guardian', 'GuardianController@readData')->name('guardian.readData');
    Route::resource('student', 'StudentController');
    Route::get('student-section', 'StudentController@section')->name('student.section');
    Route::get('student-filer', 'StudentController@filter')->name('student.filter');
    Route::resource('session', 'SessionYearController');
});
