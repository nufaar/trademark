<x-admin-layout>
    <x-slot:title>
        Edit Role
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('role.index') }}">Role</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Role
        </li>
    </x-slot:menu>

    <livewire:roles.edit :role="$role" />

</x-admin-layout>
