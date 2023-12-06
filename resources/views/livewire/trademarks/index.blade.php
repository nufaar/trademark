<?php

use App\Models\Trademark;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {

    public function with()
    {
        return [
            'trademarks' => Trademark::all()
        ];
    }

    public function destroy($id)
    {
        $trademark = Trademark::findOrFail($id);
        if ($trademark->logo && file_exists(storage_path('app/public/logos/' . $trademark->logo))) {
            unlink(storage_path('app/public/logos/' . $trademark->logo));
        }
        if ($trademark->certificate && file_exists(storage_path('app/public/certificates/' . $trademark->certificate))) {
            unlink(storage_path('app/public/certificates/' . $trademark->certificate));
        }
        if ($trademark->signature && file_exists(storage_path('app/public/signatures/' . $trademark->signature))) {
            unlink(storage_path('app/public/signatures/' . $trademark->signature));
        }

        Trademark::destroy($id);
        session()->flash('success', 'Data berhasil dihapus.');
    }

}; ?>

<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="card-title">
                Daftar Permohonan Merek
            </h5>
            <a href="{{ route('trademark.create') }}" class="btn btn-primary icon icon-left"><i
                    class="bi bi-person-add"></i>
                Tambah Permohonan</a>
        </div>
        <div class="card-body">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Logo</th>
                    <th>Merek</th>
                    <th>Pemilik</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trademarks as $trademark)
                    <tr>
                        <td><img src="{{ asset('storage/logos/' . $trademark->logo) }}"
                                 alt="logo {{ $trademark->name }}" width="100"></td>
                        <td>{{ $trademark->name }}</td>
                        <td>{{ $trademark->owner }}</td>
                        <td>
                            <div class="btn-group mb-3 btn-group-sm" role="group" aria-label="Basic example">
                                <a href="{{ route('trademark.edit', ['trademark' => $trademark->id]) }}"
                                   class="btn icon btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <button wire:click="destroy({{ $trademark->id }})" class="btn icon btn-sm btn-primary">
                                    <i class="bi bi-x-lg"></i></button>
                            </div>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
