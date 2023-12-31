<?php

use App\Models\Trademark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.admin')]
class extends Component {
    public Trademark $trademark;

}; ?>

<div>
    <x-slot:title>
        Rincian Permohonan
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}">Permohonan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Rincian Trademark
        </li>
    </x-slot:menu>

    <div class="row">
        <div class="mb-3">
            <a href="{{ route('trademark.index') }}" class="h6 icon"><i class="bi bi-arrow-left"></i> Kembali ke daftar
                permohonan</a>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <div class="avatar avatar-2xl">
                            <img class="object-fit-cover" src="{{ asset('storage/logos/' . $trademark->logo) }}" alt="Avatar">
                        </div>

                        <h3 class="mt-3">{{ $trademark->name }}</h3>
                        <div
                            class="badge bg-light-{{ config('constants.status.color.' .  $trademark->status) }}">{{ config('constants.status.text.' .  $trademark->status) }}</div>
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
                    @if($trademark->certificate)
                    <div>
                        <p>Surat Keterangan UMK</p>
                        <a href="{{ asset('storage/certificates/' . $trademark->certificate) }}" target="_blank"
                            class="btn btn-primary">Lihat</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
