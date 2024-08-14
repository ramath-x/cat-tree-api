<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found.'], 404);
});
