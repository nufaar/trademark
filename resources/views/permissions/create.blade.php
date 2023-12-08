<x-admin-layout>
    <x-slot:title>
        Tambah Permission
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Permission</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Permission
        </li>
    </x-slot:menu>

    <livewire:permissions.create />

</x-admin-layout>
