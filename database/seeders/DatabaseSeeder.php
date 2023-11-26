<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RoleHasPermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $roles = Role::all();

        foreach ($roles as $role) {
            if ($role->code == 'admin') {
                // foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15] as $permission) {
                //     DB::table('role_has_permissions')->insert([
                //         'permission_id' => $permission,
                //         'role_id' => $role->id,
                //     ]);
                // }
                $role->permissions()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]);
            } elseif ($role->code == 'manager') {
                // foreach ([11, 12, 13, 14, 15] as $permission) {
                //     DB::table('role_has_permissions')->insert([
                //         'permission_id' => $permission,
                //         'role_id' => $role->id,
                //     ]);
                // }
                $role->permissions()->sync([9, 10, 11, 12, 13]);
            } elseif ($role->code == 'user') {
                // foreach ([11, 12, 13, 14] as $permission) {
                //     DB::table('role_has_permissions')->insert([
                //         'permission_id' => $permission,
                //         'role_id' => $role->id,
                //     ]);
                // }
                $role->permissions()->sync([9, 10, 11, 12]);
            }
        }
        $this->call(UsersTableSeeder::class);
    }
}
