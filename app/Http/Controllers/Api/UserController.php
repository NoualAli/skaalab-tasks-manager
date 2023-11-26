<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateUserInfoRequest;
use App\Http\Requests\User\UpdateUserPasswordRequest;
use App\Http\Resources\LoginHistoryResource;
use App\Http\Resources\UserResource;
use App\Models\Media;
use App\Models\User;
use App\Notifications\UserCreatedNotification;
use App\Notifications\UserInforUpdatedNotification;
use App\Notifications\UserInfoUpdatedNotification;
use App\Notifications\UserPasswordUpdatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Get authenticated users.
     */
    public function current()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function loginsHistory()
    {
        return LoginHistoryResource::collection(paginate(auth()->user()->logins()->orderBy('created_at', 'DESC')->orderBy('last_activity', 'DESC')->get(), '/api/logins/history'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        isAbleOrAbort('view_users');
        $users = User::with(['role', 'last_login']);
        $filter = request('filter', null);
        $search = request('search', null);
        $sort = request('sort', null);
        $fetchFilters = request()->has('fetchFilters');
        $perPage = request('perPage', 10);
        $fetchAll = request()->has('fetchAll');

        if ($filter) {
            $users = $users->filter($filter);
        }

        if ($sort) {
            $users = $users->sortByMultiple($sort);
        }

        if ($search) {
            $users = $users->search($search);
        }

        if ($fetchAll) {
            $users = formatForSelect($users->get()->toArray(), 'full_name');
        } else {
            $perPage = request('perPage', 10);
            $users = UserResource::collection($users->paginate($perPage)->onEachSide(1));
        }

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\User\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();
            DB::transaction(function () use ($data) {
                $firstLoginPassword = $data['password'];
                $data['password'] = isset($data['password']) && !empty($data['password']) ? Hash::make($data['password'])  : Hash::make('Azerty123');
                $data['role_id'] = $data['role'];
                unset($data['role']);
                $user = User::create($data);
                if ($user->is_active) {
                    Notification::send($user, new UserCreatedNotification($user, $firstLoginPassword));
                }
            });
            return response()->json([
                'message' => CREATE_SUCCESS,
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        isAbleOrAbort('view_users');
        $user->unsetRelations();
        $user = $user->load(['role', 'last_login']);
        $user->last_login_last_activity = $user->last_login?->last_activity;
        $user->role_name = $user->role->name;
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\User\UpdateUserInfoRequest  $request
     * @param  App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateInfo(UpdateUserInfoRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data, $user) {
                $data['role_id'] = $data['role'];
                unset($data['role']);

                $user->update($data);
            });
            return response()->json([
                'message' => UPDATE_SUCCESS,
                'status' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\User\UpdateUserPasswordRequest  $request
     * @param  App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdateUserPasswordRequest $request, User $user)
    {
        try {
            return DB::transaction(function () use ($request, $user) {
                $data = $request->validated();
                $user->password = Hash::make($data['password']);
                if (auth()->user()->id !== $user->id) {
                    $user->must_change_password = true;
                }
                if ($user->save()) {
                    Notification::send($user, new UserPasswordUpdatedNotification($user, $data['password']));
                    return response()->json([
                        'message' => UPDATE_PASSWORD_SUCCESS,
                        'status' => true
                    ]);
                }
                return response()->json([
                    'message' => UPDATE_PASSWORD_ERROR,
                    'status' => false
                ]);
            });
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        isAbleOrAbort('delete_user');
        try {
            $user->delete();
            return response()->json([
                'message' => DELETE_SUCCESS,
                'status' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false
            ], 500);
        }
    }
}
