<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;

new class extends Component {
    public $name;

    public Permission $permission;

    public function mount(Permission $permission)
    {
        $this->name = $permission->name;
    }

    public function edit()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permission->id
        ]);

        $this->permission->update([
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
                <form wire:submit="edit">
                    <x-maz-form-input property="name" label="Permission" type="text" name="permission"
                                      placeholder="Enter permission"/>

                    <div class="form-group my-2 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
