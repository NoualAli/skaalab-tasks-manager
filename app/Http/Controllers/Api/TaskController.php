<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tasks\StoreRequest;
use App\Http\Requests\Tasks\UpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Notifications\NewTaskAssigned;
use App\Notifications\TaskAssignationRemoved;
use App\Notifications\TaskCreated;
use App\Notifications\TaskDeleted;
use App\Notifications\TaskUpdated;
use App\Events\PrivateTaskDeleted;
use App\Events\PrivateTaskValidated;
use App\Events\TaskUpdated as TaskUpdatedEvent;
use App\Events\TaskCreated as TaskCreatedEvent;
use App\Events\TaskValidated as TaskValidatedEvent;
use App\Events\TaskDeleted as TaskDeletedEvent;
use App\Events\TaskAssignationRemoved as TaskAssignationRemovedEvent;
use App\Events\TaskAssigned as TaskAssignedEvent;
use App\Notifications\TaskValidated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        isAbleOrAbort(['view_tasks']);
        $userId = auth()->user()->id;
        $userRole = auth()->user()->role->code;
        $canAssignTask = in_array($userRole, ['admin', 'manager']);

        if ($canAssignTask) {
            $tasks = Task::with('executer');
        } else {
            $tasks = Task::with(['creator', 'executer'])->where('created_by_id', $userId)->orWhere('assigned_to_id', $userId)->orWhereNull('assigned_to_id');
        }

        $filter = request('filter', null);
        $search = request('search', null);
        $sort = request('sort', null);
        $fetchFilters = request()->has('fetchFilters');
        $perPage = request('perPage', 10);

        if ($filter) {
            $tasks = $tasks->filter($filter);
        }

        if ($sort) {
            $tasks = $tasks->sortByMultiple($sort);
        } else {
            $tasks = $tasks->orderBy('finished_at', 'ASC')->orderBy('priority', 'DESC')->orderBy('created_at', 'ASC');
        }
        if ($search) {
            $tasks = $tasks->search($search);
        }

        return TaskResource::collection($tasks->paginate($perPage)->onEachSide(1));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by_id'] = auth()->user()->id;
        try {
            $task = Task::create($data);
            $notificationUsersList = $this->getNotificationUsers($task);

            foreach ($notificationUsersList->get() as $user) {
                if ($task->assigned_to_id && $user->id == $task->assigned_to_id) {
                    Notification::send($user, new NewTaskAssigned($task));
                    broadcast(new TaskAssignedEvent($task, $user));
                } else {
                    Notification::send($user, new TaskCreated($task));
                    broadcast(new TaskCreatedEvent($task, $user));
                }
            }
            return response()->json([
                'message' => CREATE_SUCCESS,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => env('APP_ENV') == 'production' ? CREATE_ERROR : $th->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        isAbleOrAbort('view_tasks');
        return response()->json($task->load(['creator', 'executer']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Task $task)
    {
        $data = $request->validated();
        $oldAssignation = $task->assigned_to_id;
        $data['assigned_to_id'] = isset($data['assigned_to_id']) ? $data['assigned_to_id'] : null;

        try {
            $result = $task->update($data);
            $notificationUsersList = $this->getNotificationUsers($task, [$oldAssignation]);
            $additional = ['assigned' => false, 'removed' => false, 'updated' => false];
            foreach ($notificationUsersList->get() as $user) {
                if (!is_null($oldAssignation) && $oldAssignation !== $user->id) {
                    Notification::send($user, new NewTaskAssigned($task));
                    event(new TaskAssignedEvent($task, $user));
                    $additional['assigned'] = true;
                } elseif (!is_null($oldAssignation) && $oldAssignation == $user->id && $oldAssignation !== $task->assigned_to_id) {
                    Notification::send($user, new TaskAssignationRemoved($task, $user));
                    event(new TaskAssignationRemovedEvent($task, $user));
                    $additional['removed'] = true;
                } else {
                    Notification::send($user, new TaskUpdated($task));
                    event(new TaskUpdatedEvent($task, auth()->user()));
                    $additional['updated'] = true;
                }
            }
            return response()->json([
                'message' => $result ? UPDATE_SUCCESS :  UPDATE_ERROR,
                'status' => $result,
                'additional' => $additional
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => env('APP_ENV') == 'production' ? UPDATE_ERROR : $th->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    public function validateTask(Task $task)
    {
        $user = auth()->user();
        $condition = isAbleTo('edit_task') && $task->created_by_id == $user->id || $task->assigned_to_id == $user->id || is_null($task->assigned_to_id);
        abort_if(!$condition, 403);

        try {
            $result = $task->update(['finished_at' => now(), 'validated_by_id' => auth()->user()->id]);
            if ($result) {
                $notificationUsersList = $this->getNotificationUsers($task);

                foreach ($notificationUsersList->get() as $user) {
                    if ($task->assigned_to_id && $task->assigned_to_id !== $user->id) {
                        Notification::send($user, new TaskValidated($task));
                        event(new TaskValidatedEvent($task, $user));
                    } else {
                        event(new PrivateTaskValidated($task, $user));
                    }
                }
            }
            return response()->json([
                'message' => $result ? UPDATE_SUCCESS :  UPDATE_ERROR,
                'status' => $result,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => env('APP_ENV') == 'production' ? UPDATE_ERROR : $th->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        isAbleOrAbort('delete_task');
        try {
            $notificationUsersList = $this->getNotificationUsers($task);

            foreach ($notificationUsersList->get() as $user) {
                if ($task->assigned_to_id && ($task->assigned_to_id == $user->id || $task->created_by_id == $user->id)) {
                    event(new PrivateTaskDeleted($task, $user));
                    Notification::send($user, new TaskDeleted($task));
                } else {
                    event(new TaskDeletedEvent($task, $user));
                    Notification::send($user, new TaskDeleted($task));
                }
            }
            return response()->json([
                'message' => DELETE_SUCCESS,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    private function getNotificationUsers(Task|int $task, array $additionalUsers = [])
    {
        if (is_int($task)) {
            $task = Task::where('created_by_id', $task)->orWhere('assigned_to_id', $task);
        }
        if ($task->assigned_to_id == auth()->user()->id) {
            $users = User::where('id', '!=', $task->created_by_id)->orWhereIn('id', $additionalUsers);
        } elseif (!$task->assigned_to_id) {
            $users = User::where('id', '!=', auth()->user()->id)->orWhereIn('id', $additionalUsers);
        } elseif ($task->assigned_to_id !== auth()->user()->id) {
            $users = User::where('id', '=', $task->assigned_to_id)->orWhereIn('id', $additionalUsers);
        }

        return $users;
    }
}
