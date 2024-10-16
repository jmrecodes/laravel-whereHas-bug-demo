<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BugDemoController;

Route::get('/', [BugDemoController::class, 'demonstrateBug']);

