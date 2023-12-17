<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;

new class extends Component {
    public $name;

    public function store()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create([
            'name' => $this->name
        ]);

        session()->flash('success', 'Permission berhasil ditambahkan');

        $this->redirect(route('permission.index'));
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form wire:submit="store">
                    <x-maz-form-input property="name" label="Permission" type="text" name="permission"
                                      placeholder="Masukan permission"/>

                    <div class="form-group my-2 d-flex justify-content-end flex-column flex-sm-row">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
