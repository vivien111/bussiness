<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Wave\Facades\Wave;
use App\Http\Controllers\AnnouncementController;

// Wave routes
Wave::routes();

Route::get('/get-states/{country_id}', [AnnouncementController::class, 'getStates'])
     ->name('get.states');

Route::get('dashboard/announcements/create', [AnnouncementController::class, 'create'])
     ->name('wave.dashboard.announcements.create');




