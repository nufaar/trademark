<x-admin-layout>
    <x-slot:title>
        Profil
    </x-slot:title>

    <x-slot:subtitle>
        Ubah profil kamu.
    </x-slot:subtitle>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profil
        </li>
    </x-slot:menu>

    <livewire:users.profile.index />

    <script>
        const checkbox = document.getElementById("iaggree")
        const buttonDeleteAccount = document.getElementById("btn-delete-account")
        checkbox.addEventListener("change", function() {
            const checked = checkbox.checked
            console.log(checked)
            if (checked) {
                buttonDeleteAccount.removeAttribute("disabled")
            } else {
                buttonDeleteAccount.setAttribute("disabled", true)
            }
        })
    </script>

</x-admin-layout>
