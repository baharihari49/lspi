<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/struktur-organisasi', function () {
    return view('struktur-organisasi');
});

Route::get('/skema', function () {
    return view('skema');
});

Route::get('/skema/penerapan-it-artikel-ilmiah', function () {
    return view('skema.penerapan-it-artikel-ilmiah');
});

Route::get('/skema/pengelolaan-jurnal-elektronik', function () {
    return view('skema.pengelolaan-jurnal-elektronik');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/news', function () {
    return view('news');
});
