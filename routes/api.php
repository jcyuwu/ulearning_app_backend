<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post("/login", [UserController::class, "login"]);
//Route::post("/auth/register", [UserController::class, "createUser"]);
//Route::post("/auth/login", [UserController::class, "loginUser"]);