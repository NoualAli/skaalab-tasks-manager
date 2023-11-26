<?php

namespace App\Models;

use App\Traits\IsSortable;
use App\Traits\IsSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Permission extends BaseModel
{
    use HasFactory, IsSearchable, IsSortable;

    protected $fillable = [
        'name',
        'guard_name',
    ];

    public $timestamps = false;

    protected $perPage = 10;

    protected $searchable = ['name'];
    /**
     * Getters
     */
    public function getRolesStrAttribute()
    {
        $roles = implode(', ', $this->roles->pluck('name')->flatten()->toArray());
        return !empty($roles) ? $roles : '-';
    }

    /**
     * Relationships
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }
}
