<?php

use Illuminate\Support\Facades\Route;



Route::view('widget','admin.partials.widget');
Route::middleware(['role:admin','auth'])->prefix('dashboard')->group(function () {
    Route::view('index', 'admin.partials.dashboard')->name('admin');
});
