<x-admin-layout>
    <x-slot:title>
        Profile
    </x-slot:title>

    <x-slot:subtitle>
        Change your current profile.
    </x-slot:subtitle>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profile
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
