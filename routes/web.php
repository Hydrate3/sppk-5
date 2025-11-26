<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DecisionController;

Route::get('/', [DecisionController::class, 'index'])->name('decision.index');
Route::post('/upload', [DecisionController::class, 'upload'])->name('decision.upload');
Route::get('/view/{session}', [DecisionController::class, 'view'])->name('decision.view');
Route::get('/weights/{session}', [DecisionController::class, 'weights'])->name('decision.weights');
Route::post('/calculate/{session}', [DecisionController::class, 'calculate'])->name('decision.calculate');
Route::get('/results/{session}', [DecisionController::class, 'results'])->name('decision.results');     
Route::get('/history', [DecisionController::class, 'history'])->name('decision.history');