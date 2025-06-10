<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormTemplateController;
use App\Http\Controllers\FormResponseController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('form-templates', FormTemplateController::class);
Route::post('/form-templates/{formId}/responses', [FormResponseController::class, 'store']);
