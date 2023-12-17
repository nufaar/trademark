<x-admin-layout>
    <x-slot:title>
        Daftar Permission
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Permission</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Permission
        </li>
    </x-slot:menu>

    <livewire:permissions.index />

</x-admin-layout>
