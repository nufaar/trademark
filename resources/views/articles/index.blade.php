<x-admin-layout>
    <x-slot:title>
        Pengumuman
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('article.index') }}">Daftar Pengumuman</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Pengumuman
        </li>
    </x-slot:menu>

    <livewire:articles.index />

</x-admin-layout>
