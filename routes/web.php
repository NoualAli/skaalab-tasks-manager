<?php

use App\Events\TaskUpdated;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ExportController;
use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('excel-export')->controller(ExportController::class)->group(function () {
    Route::get('/', 'export');
});

// Route::get('test-broadcasting', function () {
//     $task = Task::first();
//     event(new TaskUpdated($task));
// });

// Broadcast::routes();

Route::get('logout', [LoginController::class, 'logout']);

if (env('APP_ENV') !== 'production' && env('APP_ENV') !== 'production-test') {
    Route::get('update-passwords', function () {
        return DB::table('users')->update([
            'password' => Hash::make('123456')
        ]);
    });
}
