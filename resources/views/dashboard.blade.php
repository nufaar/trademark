<x-admin-layout>
   <livewire:dashboard />

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('cek-dispatch', (event) => {
                console.log('dispatched');
            });
        });
    </script>
</x-admin-layout>
