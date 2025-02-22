<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login'); 

    Route::get('user', 'userProfile')->middleware('auth:sanctum');
    Route::post('logout', 'userLogout')->middleware('auth:sanctum');
});

// Group routes that require authentication
Route::middleware('auth:sanctum')->group(function() {
    
    // Route for user to apply for a course
    Route::post('/courses', [CourseController::class, 'createCourse']);
    Route::post('/courses/apply', [CourseController::class, 'apply']);

    // Route for viewing users who have applied for a specific course
    Route::get('/courses/{courseId}/applied-users', [CourseController::class, 'getAppliedUsers']);

    // **Reviews & Ratings Feature**
    Route::post('/reviews', [ReviewController::class, 'store']); // Add a review
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']); // Delete a review
});

// Public routes
Route::get('/courses', [CourseController::class, 'getAllCourses']);
Route::get('/courses/{courseId}/reviews', [ReviewController::class, 'index']); // Get reviews for a course
