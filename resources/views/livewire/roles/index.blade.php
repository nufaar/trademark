<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $perPage = 5;

    public function with()
    {
        return [
            'roles' => Role::query()
                ->when($this->search, function ($query) {
                    return scopeSearch($query, $this->search, ['name']);
                })
                ->paginate($this->perPage)
        ];
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'message' => 'Peran berhasil dihapus'
        ]);
    }
}; ?>

<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>
                <input wire:model.live.debounce300ms="search" type="text" class="form-control"
                       placeholder="Cari...">
            </div>
            @can('create role')
                <a href="{{ route('role.create') }}" class="btn btn-primary icon icon-left"><i
                        class="bi bi-person-add"></i> Tambah Peran</a>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Peran</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="btn-group mb-3 btn-group-sm" role="group" aria-label="Basic example">
                                @can('edit role')
                                    <a href="{{ route('role.edit', ['role' => $role->id]) }}"
                                       class="btn icon btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                @endcan
                                @can('delete role')
                                    <button wire:click="destroy({{ $role->id }})" class="btn icon btn-sm btn-primary">
                                        <i class="bi bi-x-lg"></i></button>
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
            <div>{{ $roles->links() }}</div>
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
