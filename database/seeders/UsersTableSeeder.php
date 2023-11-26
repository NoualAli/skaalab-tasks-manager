<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('users')->delete();

        \DB::table('users')->insert(array(
            0 =>
            array(
                'id' => 1,
                'username' => 'ADMIN',
                'first_name' => 'Ali',
                'last_name' => 'Noual',
                'email' => 'noualdev@gmail.com',
                'phone' => NULL,
                'avatar' => NULL,
                'gender' => 1,
                'password' => '$2y$10$nBBksEXxNQX2DoVt6DUUceJTgKbcAEnUTAa14RddQWSFUjM.FAt.m',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2023-11-24 18:32:46',
                'updated_at' => '2023-11-24 18:32:46',
                'role_id' => 1,
            ),
            1 =>
            array(
                'id' => 2,
                'username' => 'MANAGER',
                'first_name' => NULL,
                'last_name' => NULL,
                'email' => 'manager@tm.com',
                'phone' => NULL,
                'avatar' => NULL,
                'gender' => 1,
                'password' => '$2y$10$uSLSk./qNo.tOE.LEw.FeuTO1kHIoPciqSM9zo5QWXtK5gNeXtl7K',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2023-11-24 18:32:46',
                'updated_at' => '2023-11-24 18:32:46',
                'role_id' => 2,
            ),
            2 =>
            array(
                'id' => 3,
                'username' => 'USER',
                'first_name' => NULL,
                'last_name' => NULL,
                'email' => 'user@tm.com',
                'phone' => NULL,
                'avatar' => NULL,
                'gender' => 2,
                'password' => '$2y$10$yGqo14vX.q5tVJX39/CpTec6DaV0t.DG.6Gj76UjvtGRKL1QQ/RfO',
                'is_active' => 1,
                'remember_token' => NULL,
                'created_at' => '2023-11-24 18:32:46',
                'updated_at' => '2023-11-24 18:32:46',
                'role_id' => 3,
            ),
        ));
    }
}
