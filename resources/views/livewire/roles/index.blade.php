<?php

use Livewire\Volt\Component;
use Spatie\Permission\Models\Role;

new class extends Component {
    public function with()
    {
        return [
            'roles' => Role::all()
        ];
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
    }
}; ?>

<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">
                Daftar Role
            </h5>
            <a href="{{ route('role.create') }}" class="btn btn-primary icon icon-left"><i
                    class="bi bi-person-add"></i> Tambah Role</a>
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
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="btn-group mb-3 btn-group-sm" role="group" aria-label="Basic example">
                                <a href="{{ route('role.edit', ['role' => $role->id]) }}"
                                   class="btn icon btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <button wire:click="destroy({{ $role->id }})" class="btn icon btn-sm btn-primary">
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
