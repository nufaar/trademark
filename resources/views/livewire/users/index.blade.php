<?php

use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $perPage = 5;
    public $role = '';


    public function with()
    {
        return [
            'users' => User::with('roles')
                ->when($this->search, function ($query) {
                    return scopeSearch($query, $this->search, ['name', 'email']);
                })
                ->when($this->role, function ($query) {
                    return $query->whereHas('roles', function ($query) {
                        return $query->where('name', $this->role);
                    });
                })
                ->paginate($this->perPage),
            'roles' => Role::all(),
        ];
    }

    public function destroy($id)
    {
        User::destroy($id);

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'message' => 'Data berhasil dihapus'
        ]);
    }
}; ?>

<div>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-3">
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <div>
                        <input wire:model.live.debounce300ms="search" type="text" class="form-control"
                               placeholder="Cari...">
                    </div>
                    <div>
                        <select wire:model.live="role" class="form-select">
                            <option value="">Semua</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <a href="{{ route('user.create') }}" class="btn btn-primary icon icon-left"><i
                        class="bi bi-person-add"></i> Tambah User</a>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped " id="table1">
                <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Peran</th>
                    <th>Aksi</th>
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
                                @can('edit user')
                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}"
                                       class="btn icon btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('delete user')
                                    <button wire:click="destroy({{ $user->id }})" class="btn icon btn-sm btn-primary"><i
                                            class="bi bi-x-lg"></i></button>
                                @endcan
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mx-4 mb-4 mt-3">
            <div>
                <select wire:model.live="perPage" class="form-select">
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                </select>
            </div>
            <div>{{ $users->links() }}</div>
        </div>
    </div>

    @if(session('success'))
        <span class="d-none" id="success">{{ session('success') }}</span>
    @endif
</div>

@script
<script>
    let cek = document.getElementById('success')
    if (cek) {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: cek.innerText,
        })
    }

    $wire.on('showAlert', function (data) {
        Swal.fire({
            icon: data[0].icon,
            title: data[0].title,
            text: data[0].message,
        })
    })

</script>
@endscript
