<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/booking', function () {
    return view('booking.create');
})->name('booking.create');
