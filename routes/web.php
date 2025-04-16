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
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\NewsletterController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Wave routes
Wave::routes();

Route::get('/csrf-token', function () {
     return response()->json(['csrf_token' => csrf_token()]);
 });

 //Route::get('/{folder}/{filename}', function ($folder) {
     // Récupérer tous les fichiers dans le dossier spécifié
     //$files = Storage::files('public/' . $folder);
 
     // Créer des liens complets vers les fichiers
     //$fileLinks = array_map(function ($file) {
        // return url('storage/' . basename($file)); // Crée un lien direct vers le fichier
    // }, $files);
 
    // return view('storage-files', ['files' => $fileLinks]);
 //})->where('folder', '.*');

Route::get('/public/storage/announcements/{filename}', function ($filename) {
    $path = storage_path('app/public/storage/announcements/' . $filename);
    
    if (!file_exists($path)) {
        abort(404);
    }

    return response()->file($path);
})->where('filename', '.*');

 
 Route::middleware(['auth'])->group(function () {
     Route::get('/subscribe', [SubscriptionController::class, 'showForm'])->name('subscribe.form');
     Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscribe.create');
     Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('subscribe.cancel');
     Route::get('/subscription/{plan}', [SubscriptionController::class, 'create'])->name('subscription.create');
     Route::post('/subscription', [SubscriptionController::class, 'store'])->name('subscription.store');
     Route::get('/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
     Route::get('/subscription/invoice/{invoice}', [SubscriptionController::class, 'downloadInvoice'])->name('subscription.invoice');
 });

Route::post('/generate-ads', [CohereController::class, 'generateAdText']);

Route::post('/generate-ad', [OpenAIController::class, 'generateAdText']);

Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');

Route::get('/get-states/{country_id}', [AnnouncementController::class, 'getStates'])
     ->name('get.states');

     Route::get('/send-newsletter', [NewsletterController::class, 'sendTestNewsletter']);

// Version avec toutes les bonnes pratiques
Route::post('/dashboard/announcements', [AnnouncementController::class, 'store'])
    ->name('announcements.store')
    ->middleware(['auth', 'verified']); // Middlewares typiques
Route::get('/dashboard/announcements', [AnnouncementController::class, 'index']) ->name('announcements.index');
Route::get('dashboard/announcements/create', [AnnouncementController::class, 'create'])
     ->name('wave.dashboard.announcements.create');




