<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Créer les utilisateurs',
                'code' => 'create_user',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Modifier les utilisateurs',
                'code' => 'edit_user',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Supprimer les utilisateurs',
                'code' => 'delete_user',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Voir les utilisateurs',
                'code' => 'view_users',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Créer les rôles utilisateur',
                'code' => 'create_role',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Modifier les rôles utilisateur',
                'code' => 'edit_role',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Supprimer les rôles utilisateur',
                'code' => 'delete_role',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Voir les rôles utilisateur',
                'code' => 'view_roles',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Créer les tâches',
                'code' => 'create_task',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Modifier les tâches',
                'code' => 'edit_task',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Supprimer les tâches',
                'code' => 'delete_task',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Voir les tâches',
                'code' => 'view_tasks',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Assigner les tâches',
                'code' => 'assign_task',
            ),
        ));
        
        
    }
}