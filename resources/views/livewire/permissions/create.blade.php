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

        $this->redirect(route('permission.index'));
    }
}; ?>

<div>
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambahkan Permission</h5>
            </div>
            <div class="card-body">
                <form wire:submit="store">
                    <x-maz-form-input property="name" label="Permission" type="text" name="permission"
                                      placeholder="Enter permission"/>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
