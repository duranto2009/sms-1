<?php

use Illuminate\Support\Facades\Route;



Route::view('widget','admin.partials.widget');
Route::view('admin', 'admin.partials.dashboard')->name('admin');
