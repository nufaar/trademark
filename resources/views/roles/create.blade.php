<x-admin-layout>
    <x-slot:title>
        Tambah Role
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Role
        </li>
    </x-slot:menu>

    <livewire:roles.create />

</x-admin-layout>
