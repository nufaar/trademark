<x-admin-layout>
    <x-slot:title>
        Tambah Users
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Users
        </li>
    </x-slot:menu>

    <livewire:users.create />
</x-admin-layout>
