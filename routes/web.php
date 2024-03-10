<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['check_form_progress.step1and2'])->group(function () {
    Route::get('/form/step1', [FormController::class, 'step1']);
    Route::post('/form/step1', [FormController::class, 'postStep1']);


    Route::get('/form/step2', [FormController::class, 'step2']);
    Route::post('/form/step2', [FormController::class, 'postStep2']);
});

    Route::get('/form/step3', [FormController::class, 'step3']);
    Route::post('/form/step3', [FormController::class, 'postStep3']);

    Route::get('/form/step4', [FormController::class, 'step4']);
    Route::post('/form/step4', [FormController::class, 'postStep4']);

    Route::get('/form/step5', [FormController::class, 'step5']);
    Route::post('/form/step5', [FormController::class, 'postStep5']);

    Route::get('/form/step6', [FormController::class, 'step6']);
    Route::post('/form/step6', [FormController::class, 'postStep6']);

