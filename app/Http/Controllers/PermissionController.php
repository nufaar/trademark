<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }
}
