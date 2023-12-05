<?php

use App\Models\User;
use Livewire\Volt\Component;

new class extends Component {
    public function with()
    {
        return [
            'users' => User::all()
        ];
    }

    public function destroy($id)
    {
        User::destroy($id);
    }
}; ?>

<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">
                Daftar User
            </h5>
            <a href="{{ route('user.create') }}" class="btn btn-primary icon icon-left"><i class="bi bi-person-add"></i> Tambah User</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                        <td>
                            <div class="btn-group mb-3 btn-group-sm" role="group" aria-label="Basic example">
                                <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn icon btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <button wire:click="destroy({{ $user->id }})" class="btn icon btn-sm btn-primary"><i class="bi bi-x-lg"></i></button>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
