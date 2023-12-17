<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

new class extends Component {
    use WithPagination;

    public $search = '';
    public $perPage = 5;

    public function with()
    {
        return [
            'permissions' => Permission::query()
                ->when($this->search, function ($query) {
                    return scopeSearch($query, $this->search, ['name']);
                })
                ->paginate($this->perPage)
        ];
    }

    public function destroy($id)
    {
        Permission::findOrFail($id)->delete();

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil',
            'message' => 'Permission berhasil dihapus'
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
            <a href="{{ route('permission.create') }}" class="btn btn-primary icon icon-left"><i
                    class="bi bi-person-add"></i> Tambah Permission</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Permission</th>
                    <th>Aksi</th>
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
        <div class="d-flex justify-content-between mx-4 mb-4 mt-3">
            <div>
                <select wire:model.live="perPage" class="form-select">
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                </select>
            </div>
            <div>{{ $permissions->links() }}</div>
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
