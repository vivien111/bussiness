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
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OpenAIController;
use App\Http\Controllers\CohereController;

// Wave routes
Wave::routes();

Route::get('/csrf-token', function () {
     return response()->json(['csrf_token' => csrf_token()]);
 });
 

Route::post('/generate-ads', [CohereController::class, 'generateAdText']);

Route::post('/generate-ad', [OpenAIController::class, 'generateAdText']);

Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

Route::get('/get-states/{country_id}', [AnnouncementController::class, 'getStates'])
     ->name('get.states');

// Version avec toutes les bonnes pratiques
Route::post('/dashboard/announcements', [AnnouncementController::class, 'store'])
    ->name('announcements.store')
    ->middleware(['auth', 'verified']); // Middlewares typiques
Route::get('/dashboard/announcements', [AnnouncementController::class, 'index']) ->name('announcements.index');
Route::get('dashboard/announcements/create', [AnnouncementController::class, 'create'])
     ->name('wave.dashboard.announcements.create');




