<x-admin-layout>
    <x-slot:title>
        Edit Permission
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('permission.index') }}">Permission</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit Permission
        </li>
    </x-slot:menu>

    <livewire:permissions.edit :permission="$permission"/>

</x-admin-layout>
