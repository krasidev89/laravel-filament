<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Livewire::setScriptRoute(function ($handle) {
    return config('app.debug')
        ? Route::get(parse_url(url('/livewire/livewire.js'))['path'], $handle)
        : Route::get(parse_url(url('/livewire/livewire.min.js'))['path'], $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(parse_url(url('/livewire/update'))['path'], $handle);
});

Route::get('/', function () {
    return redirect()->route('filament.admin.pages.dashboard');
});
