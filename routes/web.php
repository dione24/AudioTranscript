<?php

use App\Http\Controllers\TranscriptionController;
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

Route::get('/', [TranscriptionController::class, 'index'])->name('home');

Route::get('/transcription', [TranscriptionController::class, 'index'])->name('transcription');

Route::post('/transcribe', [TranscriptionController::class, 'transcribe'])->name('transcribe');