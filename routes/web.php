<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});

Route::get('/booking', function () {
    return view('booking.create');
})->name('booking.create');
