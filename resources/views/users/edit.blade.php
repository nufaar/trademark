<x-admin-layout>
    <x-slot:title>
        Edit Users
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Users
        </li>
    </x-slot:menu>

    <livewire:users.edit :user="$user" />
</x-admin-layout>
