<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;

Route::resource('/categories', CategoryController::class);
Route::resource('/tasks', TaskController::class);
