<x-admin-layout>
    <x-slot:title>
        Tambah Merek Dagang
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}">Trademark</a></li>
        <li class="breadcrumb-item active" aria-current="page">Create Trademark
        </li>
    </x-slot:menu>

{{--    <x-slot name="head">--}}
{{--        <link rel="stylesheet" href="{{ asset('assets/extensions/filepond/filepond.css') }}">--}}
{{--        <link rel="stylesheet" href="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.css') }}">--}}
{{--        <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">--}}
{{--    </x-slot>--}}

{{--    <x-slot name="script">--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-image-crop/filepond-plugin-image-crop.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-image-filter/filepond-plugin-image-filter.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond-plugin-image-resize/filepond-plugin-image-resize.min.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/filepond/filepond.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>--}}
{{--        <script src="{{ asset('assets/static/js/pages/filepond.js') }}"></script>--}}
{{--    </x-slot>--}}

    <livewire:trademarks.create />

</x-admin-layout>
