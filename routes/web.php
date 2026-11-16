<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::get('/', function () {
    return response()->json([
        'status' => 'online',
        'message' => 'Mohammed Alhwaiti - 3D Portfolio API is running successfully',
        'version' => '1.0.0',
        'endpoints' => [
            'portfolio' => url('/api/portfolio'),
            'projects' => url('/api/projects'),
            'articles' => url('/api/articles'),
            'certificates' => url('/api/certificates'),
            'cv' => url('/api/cv'),
            'cv_download' => url('/api/cv/download'),
            'contact' => url('/api/contact'),
        ],
    ]);
});
