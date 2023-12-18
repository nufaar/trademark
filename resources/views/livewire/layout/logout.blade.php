<?php

use Livewire\Volt\Component;
use App\Livewire\Actions\Logout;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/');
    }
}; ?>

<div>
    <li><a wire:click="logout" class="dropdown-item" href="#"><i class="icon-mid bi bi-box-arrow-left me-2"></i>
            Keluar</a></li>
</div>
