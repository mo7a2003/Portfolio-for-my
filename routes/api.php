<?php

use App\Http\Controllers\Api\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\Api\Admin\ContactMessageController as AdminContactMessageController;
use App\Http\Controllers\Api\Admin\CvController as AdminCvController;
use App\Http\Controllers\Api\Admin\MediaUploadController as AdminMediaUploadController;
use App\Http\Controllers\Api\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Api\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Api\Admin\SkillController as AdminSkillController;
use App\Http\Controllers\Api\Admin\SocialLinkController as AdminSocialLinkController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\CertificateController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\CvController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\VisitController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Portfolio Endpoints
|--------------------------------------------------------------------------
| Optimized for high-throughput single-page / 3D client applications.
*/
Route::get('/portfolio', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slugOrId}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slugOrId}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

// Public CV endpoints
Route::get('/cv/download', [CvController::class, 'download'])->name('cv.download');
Route::get('/cv', [CvController::class, 'show'])->name('cv.show');

// Public contact form submission with throttling (5 requests per minute)
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// Genuine page visit tracking & analytics with rate limiting
Route::post('/visit', [VisitController::class, 'record'])
    ->middleware('throttle:60,1')
    ->name('visit.record');
Route::get('/visit/stats', [VisitController::class, 'stats'])
    ->name('visit.stats');

/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function (): void {
    Route::post('/login', [AdminAuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('admin.login');

    /*
    |--------------------------------------------------------------------------
    | Admin Protected Endpoints (Sanctum Guarded)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/me', [AdminAuthController::class, 'me'])->name('admin.me');
        Route::get('/overview-stats', [AdminAuthController::class, 'overviewStats'])->name('admin.overview-stats');

        // Profile Settings
        Route::get('/profile', [AdminProfileController::class, 'show'])->name('admin.profile.show');
        Route::match(['put', 'patch'], '/profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

        // Projects CRUD
        Route::apiResource('projects', AdminProjectController::class)->names('admin.projects');

        // Skills CRUD
        Route::apiResource('skills', AdminSkillController::class)->names('admin.skills');

        // Articles CRUD
        Route::apiResource('articles', AdminArticleController::class)->names('admin.articles');

        // Social Links CRUD
        Route::apiResource('social-links', AdminSocialLinkController::class)->names('admin.social-links');

        // Certificates & Awards CRUD
        Route::apiResource('certificates', AdminCertificateController::class)->names('admin.certificates');
        Route::post('/certificates/{certificate}', [AdminCertificateController::class, 'update'])->name('admin.certificates.update-post');

        // CV Management CRUD
        Route::apiResource('cv', AdminCvController::class)->names('admin.cv');
        Route::post('/cv/{cv}/activate', [AdminCvController::class, 'activate'])->name('admin.cv.activate');
        Route::get('/cv/{cv}/download', [AdminCvController::class, 'download'])->name('admin.cv.download');

        // Contact Messages Management
        Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('admin.contact-messages.index');
        Route::get('/contact-messages/{contactMessage}', [AdminContactMessageController::class, 'show'])->name('admin.contact-messages.show');
        Route::patch('/contact-messages/{contactMessage}/read', [AdminContactMessageController::class, 'markAsRead'])->name('admin.contact-messages.read');
        Route::delete('/contact-messages/{contactMessage}', [AdminContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');

        // Media Upload & Management
        Route::post('/media/upload', [AdminMediaUploadController::class, 'upload'])->name('admin.media.upload');
        Route::post('/media', [AdminMediaUploadController::class, 'upload'])->name('admin.media.store');
        Route::delete('/media', [AdminMediaUploadController::class, 'destroy'])->name('admin.media.destroy');
    });
});
