<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Permission;

new class extends Component {
    public function with()
    {
        return [
            'permissions' => Permission::all()
        ];
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();
    }
}; ?>

<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">
                Daftar Permission
            </h5>
            <a href="{{ route('permission.create') }}" class="btn btn-primary icon icon-left"><i
                    class="bi bi-person-add"></i> Tambah Permission</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <div class="btn-group mb-3 btn-group-sm" role="group" aria-label="Basic example">
                                <a href="{{ route('permission.edit', ['permission' => $permission->id]) }}"
                                   class="btn icon btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <button wire:click="destroy({{ $permission->id }})" class="btn icon btn-sm btn-primary">
                                    <i class="bi bi-x-lg"></i></button>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
