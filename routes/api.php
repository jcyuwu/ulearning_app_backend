<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\Api\UserController;
//use App\Http\Controllers\Api\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::post("/auth/register", [UserController::class, "createUser"]);
//Route::post("/auth/login", [UserController::class, "loginUser"]);

//Route::post("/login", [UserController::class, "login"]);
Route::namespace('App\Http\Controllers\Api')->group(function() {
//Route::group(["namespace"=>"Api"], function() {
    Route::post("/login", "UserController@login");

    Route::group(["middleware"=>["auth:sanctum"]], function() {
        Route::any("/courseList", "CourseController@courseList");
        Route::any("/courseDetail", "CourseController@courseDetail");
        Route::any("/lessonList", "LessonController@lessonList");
        Route::any("/lessonDetail", "LessonController@lessonDetail");
    });
});