<x-admin-layout>
    <x-slot:title>
        Roles
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
        <li class="breadcrumb-item active" aria-current="page">List Role
        </li>
    </x-slot:menu>

    <livewire:roles.index />

</x-admin-layout>
