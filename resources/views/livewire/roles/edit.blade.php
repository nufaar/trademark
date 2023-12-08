<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component {
    public $name;
    public $data = [];

    public Role $role;

    public function mount(Role $role)
    {
        $this->name = $role->name;
        $this->data = $role->permissions->pluck('name')->toArray();
    }

    public function with()
    {
        return [
            'permissions' => Permission::all()
        ];
    }

    public function edit()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->role->id
        ]);

        $this->role->update([
            'name' => $this->name
        ]);

        $this->redirect(route('role.index'));
    }

    public function assign()
    {
        $this->role->syncPermissions($this->data);

        $this->redirect(route('role.index'));
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Role</h5>
            </div>
            <div class="card-body">
                <form wire:submit="edit">
                    <x-maz-form-input property="name" label="Role" type="text" name="role"
                                      placeholder="Enter role"/>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Assign Role Permission</h5>
            </div>
            <div class="card-body">
                <form wire:submit="assign">
                    @foreach($permissions as $permission)
                        <div class="checkbox" >
                            <input wire:model="data" type="checkbox" id="publish" class="form-check-input" value="{{ $permission->name }}" @if($role->hasPermissionTo($permission->name)) checked @endif>
                            <label for="publish">{{ $permission->name }}</label>
                        </div>
                    @endforeach
                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
