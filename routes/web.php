<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
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

// Quick Admin Setup endpoint for Production Database initialization
Route::get('/create-admin', function () {
    $user = User::updateOrCreate(
        ['email' => 'mohamhwaty@gmail.com'],
        [
            'name' => 'Mohammed Alhwaiti',
            'password' => Hash::make('Moh2oo3$$'),
            'email_verified_at' => now(),
        ]
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Admin user created/updated successfully!',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ],
    ]);
});

// One-click full database seeding endpoint (Admin User + Portfolio Data)
Route::get('/seed-database', function () {
    Artisan::call('db:seed', ['--force' => true]);

    return response()->json([
        'status' => 'success',
        'message' => 'Database seeded successfully with Admin User and complete Portfolio content!',
        'output' => Artisan::output(),
    ]);
});
