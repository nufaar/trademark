<x-admin-layout>
    <x-slot:title>
        Tambah Pengumuman
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('article.index') }}">Pengumuman</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Pengumuman
        </li>
    </x-slot:menu>

    <livewire:articles.create />

</x-admin-layout>
