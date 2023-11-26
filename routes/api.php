<?php

use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Settings\PasswordController;
use App\Http\Controllers\Api\Settings\SettingController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Broadcast::routes();
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('user', [UserController::class, 'current']);

    Route::group(['middleware' => 'is_active'], function () {

        // Route::patch('settings/profile/{user}', [ProfileController::class, 'update']);
        Route::patch('settings/password/{user}', [PasswordController::class, 'update']);
        Route::get('settings/laravel/rules', [SettingController::class, 'getValidationRules']);

        // Roles routes
        Route::prefix('roles')->controller(RoleController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('{role}', 'show');
            Route::post('/', 'store');
            Route::put('{role}', 'update');
            Route::delete('{role}', 'destroy');
        });

        // Users
        Route::prefix('users')->controller(UserController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('{user}', 'show');
            Route::get('/logins/history', 'loginsHistory');
            Route::post('/', 'store');
            Route::put('info/{user}', 'updateInfo');
            Route::put('password/{user}', 'updatePassword');
            Route::delete('{user}', 'destroy');
        });

        /**
         * Media
         */
        Route::prefix('media')->controller(MediaController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('{media}', 'show');
            Route::post('/', 'store');
            Route::delete('{media}', 'destroy');
            Route::delete('{media}/multiple', 'destroyMultiple');
        });

        /**
         * Notifications
         */
        Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
            Route::get('/', 'index');
            Route::put('/', 'update');
            Route::get('unreadNotifications', 'unreadNotifications');
        });

        /**
         * Tasks
         */
        Route::prefix('tasks')->controller(TaskController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('{task}', 'show');
            Route::post('/', 'store');
            Route::put('{task}', 'update');
            Route::put('{task}/validate', 'validateTask');
            Route::delete('{task}', 'destroy');
        });
    });
});

// Auth
Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    // Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    // Route::post('password/reset', [ResetPasswordController::class, 'reset']);
});
