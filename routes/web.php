<?php

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

Route::get('/', function () {
    return view('app', ['component' => 'form-list', 'title' => 'Lista de Formularios']);
});

Route::get('/form/{formId}', function ($formId) {
    return view('app', [
        'component' => 'form-view',
        'formId' => $formId
    ]);
});

Route::get('/form-new', function () {
    return view('app', [
        'component' => 'form-template',
    ]);
});

Route::get('/form-edit/{formId}', function ($formId) {
    return view('app', [
        'component' => 'form-edit',
        'formId' => $formId
    ]);
});

Route::get('/new-responses/{formId}', function ($formId) {
    return view('app', [
        'component' => 'form-renderer',
        'formId' => $formId
    ]);
});

// Redirige cualquier otra ruta al inicio
Route::get('/{any}', function () {
    return redirect('/');
})->where('any', '.*');