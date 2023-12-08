<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new class extends Component {
    public $name;
    public $data = [];

    public function with()
    {
        return [
            'permissions' => Permission::all()
        ];
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:roles,name'
        ]);

        $role = Role::create([
            'name' => $this->name
        ]);

        $role->syncPermissions($this->data);

        $this->redirect(route('role.index'));
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Role</h5>
            </div>
            <div class="card-body">
                <form wire:submit="store">
                    <x-maz-form-input property="name" label="Role" type="text" name="role"
                                      placeholder="Enter role"/>

                    @foreach($permissions as $permission)
                        <div class="checkbox" >
                            <input wire:model="data" type="checkbox" id="publish" class="form-check-input" value="{{ $permission->name }}">
                            <label for="publish">{{ $permission->name }}</label>
                        </div>
                    @endforeach

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
