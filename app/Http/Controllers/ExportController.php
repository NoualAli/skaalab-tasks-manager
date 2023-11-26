<?php

namespace App\Http\Controllers;

use App\Exports\LoginsExport;
use App\Exports\ModulesExport;
use App\Exports\RolePermissionsExport;
use App\Exports\RolesExport;
use App\Exports\UsersExport;
use App\Models\Module;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        if (hasRole(['admin', 'root'])) {
            $exportValue = $request->export;
            if ($exportValue == 'users') {
                return $this->users($request);
            } elseif ($exportValue == 'roles') {
                return $this->roles($request);
            } else {
                abort(400, 'L\'action ' . $exportValue . ' n\'est pas reconnue');
            }
        } else {
            abort(FORBIDDEN, 'Vous n\'êtes pas autorisé à effectuer cette action');
        }
    }

    private function users(Request $request)
    {
        if ($request->has('id')) {
            $user = User::findOrFail($request->id);
            return Excel::download(new LoginsExport($user), 'authentifications-' . Str::slug($user->full_name) . '.xlsx');
        } else {
            $users = User::with(['role', 'last_login'])->get();
            return Excel::download(new UsersExport($users), 'liste_des_utilisateurs.xlsx');
        }
    }

    private function roles(Request $request)
    {
        if ($request->has('id')) {
            $role = Role::findOrFail($request->id)->load('permissions');
            return Excel::download(new RolePermissionsExport($role), 'permissions-' . $role->code . '.xlsx');
        } else {
            $roles = Role::all();
            return Excel::download(new RolesExport($roles), 'liste_des_rôles.xlsx');
        }
    }

    private function modules()
    {
        $modules = Module::with('permissions')->get();
        return Excel::download(new ModulesExport($modules), 'liste_des_modules.xlsx');
    }
}
