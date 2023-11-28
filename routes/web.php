<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/auth/{provider}', [SocialiteController::class, 'redirectToProvider'])->name('auth');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'handleProvideCallback']);

Auth::routes();
Route::middleware(['auth', 'web'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', function () {
        return redirect()->route('home');
    });
    Route::prefix('report')->group(function () {
        Route::get('/', [App\Http\Controllers\Reports::class, 'index'])->name('report');
        Route::post('/save', [App\Http\Controllers\Reports::class, 'store'])->name('report.save');
        Route::get('/user', [App\Http\Controllers\Reports::class, 'user'])->name('report.user');
        Route::get('/get', [App\Http\Controllers\Reports::class, 'getData'])->name('report.get');
        Route::get('/getDataProfile', [App\Http\Controllers\Reports::class, 'getDataProfile'])->name('report.getDataProfile');
        Route::get('/getDataUser', [App\Http\Controllers\Reports::class, 'getDataUser'])->name('report.getDataUser');
        Route::post('/detail', [App\Http\Controllers\Reports::class, 'detail'])->name('report.detail');
        Route::post('/edit', [App\Http\Controllers\Reports::class, 'edit'])->name('report.edit');
        Route::get('/delete', [App\Http\Controllers\Reports::class, 'delete'])->name('report.delete');
        Route::get('/export', [App\Http\Controllers\Reports::class, 'export'])->name('report.export');
        Route::post('/update', [App\Http\Controllers\Reports::class, 'update'])->name('report.update');
    });
    Route::prefix('profile')->group(function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
        Route::post('/save', [App\Http\Controllers\ProfileController::class, 'store'])->name('profile.save');
        Route::get('/detail/{id}', [App\Http\Controllers\ProfileController::class, 'detail'])->name('profile.detail');
        Route::get('/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    });

    Route::prefix('suggestion')->group(function () {
        Route::get('/', [App\Http\Controllers\SuggestionController::class, 'index'])->name('suggestion');
        Route::post('/save', [App\Http\Controllers\SuggestionController::class, 'store'])->name('suggestion.save');
    });
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/get', [App\Http\Controllers\UserController::class, 'getData'])->name('users.get');
    Route::get('/detail', [App\Http\Controllers\UserController::class, 'detail'])->name('users.detail');

    Route::prefix('patrol')->group(function () {
        Route::get('/', [App\Http\Controllers\PatrolController::class, 'index'])->name('patrol');
    });
    Route::prefix('comment')->group(function () {
        Route::get('/{id}', [App\Http\Controllers\Reports::class, 'comment'])->name('comment');
        Route::post('/save', [App\Http\Controllers\Reports::class, 'commentSave'])->name('comment.save');
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('reports')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.report')->middleware('admin');
            Route::get('/get', [App\Http\Controllers\Admin\ReportController::class, 'getData'])->name('admin.report.get');
            Route::post('/detail', [App\Http\Controllers\Admin\ReportController::class, 'detail'])->name('admin.report.detail')->middleware('admin');
            Route::get('/delete', [App\Http\Controllers\Admin\ReportController::class, 'delete'])->name('admin.report.delete')->middleware('admin');
            Route::post('/edit', [App\Http\Controllers\Admin\ReportController::class, 'edit'])->name('admin.report.edit')->middleware('admin');
            Route::get('/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.report.export');
        });
        Route::prefix('patrol')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\PatrolController::class, 'index'])->name('admin.patrol')->middleware('admin');;
            Route::post('/add', [App\Http\Controllers\Admin\PatrolController::class, 'add'])->name('admin.patrol.add')->middleware('admin');;
            Route::get('/get', [App\Http\Controllers\Admin\PatrolController::class, 'getData'])->name('admin.patrol.get');
            Route::post('/detail', [App\Http\Controllers\Admin\PatrolController::class, 'detail'])->name('admin.patrol.detail')->middleware('admin');;
            Route::get('/delete', [App\Http\Controllers\Admin\PatrolController::class, 'delete'])->name('admin.patrol.delete')->middleware('admin');;
            Route::post('/edit', [App\Http\Controllers\Admin\PatrolController::class, 'edit'])->name('admin.patrol.edit')->middleware('admin');;
            Route::get('/export', [App\Http\Controllers\Admin\PatrolController::class, 'export'])->name('admin.patrol.export');
        });
        Route::prefix('users')->group(function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users')->middleware('admin');;
            Route::get('/get', [App\Http\Controllers\UserController::class, 'getData'])->name('admin.users.get')->middleware('admin');;
            Route::post('/detail', [App\Http\Controllers\UserController::class, 'detail'])->name('admin.users.detail')->middleware('admin');;
            Route::get('/delete', [App\Http\Controllers\UserController::class, 'delete'])->name('admin.users.delete')->middleware('admin');;
            Route::post('/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit')->middleware('admin');;
        });
    });
});
