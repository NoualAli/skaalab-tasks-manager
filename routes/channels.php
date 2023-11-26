<?php

use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('task-assigned-{userId}', function ($user, int $userId) {
    return true;
    // return $user->id == (int) $userId;
});

Broadcast::channel('task-assignation-removed-{userId}', function ($user, int $userId) {
    return true;
    // return $user->id == (int) $userId;
});

Broadcast::channel('task-deleted-{userId}', function ($user, int $userId) {
    return true;
    // return $user->id == (int) $userId;
});

Broadcast::channel('task-validated-{userId}', function ($user, int $userId) {
    return true;
    // return $user->id == (int) $userId;
});
