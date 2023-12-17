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

        session()->flash('success', 'Peran berhasil ditambahkan');
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit="store">
                    <x-maz-form-input property="name" label="Peran" type="text" name="role"
                                      placeholder="Masukan peran"/>

                    <div class="mt-4 row">
                    <p>Pilih Permission</p>
                        @foreach($permissions as $permission)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 my-1">
                                <div class="checkbox">
                                    <input wire:model="data" type="checkbox" id="publish" class="form-check-input"
                                           value="{{ $permission->name }}">
                                    <label for="publish">{{ $permission->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="form-group my-2 d-flex justify-content-end flex-column flex-sm-row">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
