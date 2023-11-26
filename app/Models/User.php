<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Traits\HasRoles;
use App\Traits\IsFilterable;
use App\Traits\IsSortable;
use App\Traits\IsSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,
        HasFactory,
        IsSortable,
        IsSearchable,
        IsFilterable,
        HasRoles;

    protected $filter = 'App\Filters\User';

    protected $perPage = 10;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'password',
        'last_name',
        'first_name',
        'phone',
        'must_change_password',
        'role_id',
        'gender',
        'is_active',
        'registration_number',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'is_active' => 'boolean',
        'gender' => 'integer',
    ];

    public $searchable = ['last_name', 'first_name', 'username', 'email', 'phone'];

    protected $appends = ['full_name', 'abbreviated_name', 'gender_str', 'martial_status', 'full_name_with_martial', 'permissions_arr', 'authorizations'];

    /**
     * Getters
     */

    public function getCreatedAtAttribute($created_at)
    {
        return \Carbon\Carbon::parse($created_at)->format('d-m-Y');
    }

    public function getGenderStrAttribute()
    {
        return $this->gender == 1 ? 'Homme' : 'Femme';
    }

    public function getMartialStatusAttribute()
    {
        return $this->gender == 1 ? 'Mr' : 'Mme';
    }

    public function getAuthorizationsAttribute()
    {
        $authorizations = [];
        foreach ($this->permissions_arr as $permission) {
            $authorizations[$permission] = isAbleTo($permission);
        }
        return $authorizations;
    }

    public function getPermissionsArrAttribute()
    {
        return $this->role->permissions->pluck('code')->toArray();
    }
    public function getAbbreviatedNameAttribute()
    {
        return $this->first_name && $this->last_name ? substr(strtoupper($this->first_name), 0, 1) . '.' . strtoupper($this->last_name) : $this->full_name;
    }
    public function getFullNameAttribute()
    {
        return $this->first_name && $this->last_name ? ucfirst(strtolower($this->first_name)) . ' ' . ucfirst(strtolower($this->last_name)) : $this->username;
    }

    public function getFullNameWithMartialAttribute()
    {
        return $this->full_name ? $this->martial_status . ' ' . $this->full_name : null;
    }

    public function getUsernameAttribute($username)
    {
        return strtoupper($username);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Relationships
     */

    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    public function last_login()
    {
        return $this->hasOne(Login::class)->orderBy('last_activity', 'DESC');
    }

    public function ownTasks()
    {
        return $this->hasMany(Task::class, 'created_by_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    /**
     * Scopes
     */

    public function scopeWhereRoles(Builder $query, $roles)
    {
        return $query->whereHas('role', function ($query) use ($roles) {
            if (is_array($roles)) {
                return $query->whereIn('code', $roles);
            }
            return $query->where('code', $roles);
        });
    }
}
