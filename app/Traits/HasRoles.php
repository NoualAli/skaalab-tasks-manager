<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\User;

trait HasRoles
{
    /**
     * Getters
     */
    public function getPermissionsStrAttribute()
    {
        $roles = implode(', ', $this->permissions_arr);
        return !empty($roles) ? $roles : '-';
    }
    public function getPermissionsArrAttribute()
    {
        return $this->role->permissions->pluck('name')->toArray();
    }

    /**
     * Methods
     */
    public function isAbleTo(string|array $abilities)
    {
        $permissions = $this->permissions_arr;

        if (is_array($abilities)) {
            $isAbleTo = [];
            foreach ($abilities as $ability) {
                array_push($isAbleTo, in_array($ability, $permissions));
            }
            return in_array(true, $isAbleTo);
        }
        return in_array($abilities, $permissions);
    }

    /**
     * Relationships
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
