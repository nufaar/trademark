<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }
}
