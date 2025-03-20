<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.presence');
})->name('presence');

Route::get('/presence_detail', function () {
    return view('admin.presence_detail');
})->name('presence_detail');

Route::get('/presence_create', function () {
    return view('admin.presence_create');
})->name('presence_create');

Route::get('/starter', function () {
    return view('starter');
});

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
