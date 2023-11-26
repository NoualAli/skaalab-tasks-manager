<?php

namespace App\Models;

use App\Traits\IsSortable;
use App\Traits\IsSearchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Role extends BaseModel
{
    use HasFactory, IsSearchable, IsSortable;

    protected $fillable = [
        'code',
        'name',
        'guard_name',
    ];

    public $timestamps = false;

    protected $perPage = 10;

    public $with = ['permissions'];

    protected $searchable = ['name', 'code'];

    protected $appends = ['permissions_str', 'full_name'];

    /**
     * Getters
     */
    public function getFullNameAttribute()
    {
        return strtoupper($this->code) . ' - ' . $this->name;
    }

    public function getNameAttribute($name)
    {
        $name = implode(' ', array_map(fn ($item) => ucfirst(strtolower($item)), explode(' ', $name)));
        return $name;
    }

    public function getPermissionsStrAttribute()
    {
        $permissions = $this->permissions->pluck('name')->flatten()->toArray();
        $permissions_str = '';
        foreach ($permissions as $permission) {
            $permissions_str .= '<span class="tag">' . $permission . '</span>';
        }
        return $permissions_str;
        // return !empty($permissions) ? $permissions : '-';
    }
    /**
     * Relationships
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
