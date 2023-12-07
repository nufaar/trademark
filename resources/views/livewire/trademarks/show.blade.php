<?php

use App\Models\Trademark;
use Livewire\Volt\Component;

new class extends Component {
    public Trademark $trademark;

}; ?>

<div>
    <div class="row">
        <div class="mb-3">
            <a href="{{ route('trademark.index') }}" class="h6 icon"><i class="bi bi-arrow-left"></i> Kembali ke daftar permohonan</a>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl">
                            <img src="{{ asset('storage/logos/' . $trademark->logo) }}" alt="Avatar">
                        </div>

                        <h3 class="mt-3">{{ $trademark->name }}</h3>
                        <div
                            class="badge bg-light-{{ config('constants.status.color.' .  $trademark->status) }}">{{ $trademark->status }}</div>
                        @if($trademark->comment)
                            <p class="fs-6 mt-1">{{ $trademark->comment }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-0">Pemilik Merek</p>
                        <h5>{{ $trademark->owner }}</h5>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0">Alamat</p>
                        <h5>{{ $trademark->address }}</h5>
                    </div>
                    <div class="mb-3">
                        <p class="mb-0">Tanda Tangan Pemohon</p>
                        <img src="{{ asset('storage/signatures/' . $trademark->signature) }}" alt="" width="200">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
