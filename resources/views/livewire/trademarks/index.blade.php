<?php

use App\Models\Trademark;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Volt\Component;

new class extends Component {

    public $comment = '';
    public $status;
    public $trademark_id = 0;

    public Trademark $trademark;

    // dibuat renderless agar modal tidak tertutup karena render ulang
    #[Renderless]
    public function change_property($id, $status = 'revision')
    {
        $this->trademark_id = $id;
        $this->status = $status;
    }

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

    public function verif()
    {

        $this->validate([
            'comment' => 'required|string'
        ]);

        $trademark = Trademark::findOrFail($this->trademark_id);

        $trademark->update([
            'status' => $this->status,
            'comment' => $this->comment,
        ]);

        return $this->redirect(route('trademark.index'), navigate: true);
    }

    public function verifApproved($id)
    {
        $trademark = Trademark::findOrFail($id);

        $trademark->update([
            'status' => 'approved',
            'comment' => "Permohonan disetujui",
        ]);

        return $this->redirect(route('trademark.index'), navigate: true);
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
                    <th>Permohonan</th>
                    <th>Status</th>
                    <th>Verifikasi</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trademarks as $trademark)
                    <div wire:key="{{ $trademark->id }}">
                        <tr>

                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <img src="{{ asset('storage/logos/' . $trademark->logo) }}"
                                             alt="logo {{ $trademark->name }}" width="75">
                                    </div>
                                    <div>
                                        <h5>{{ $trademark->name }}</h5>
                                        <small>{{ $trademark->owner }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div
                                    class="badge rou nded-pill bg-light-{{ config('constants.status.color.' . $trademark->status) }}">{{ $trademark->status }}</div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <form wire:submit="verifApproved('{{ $trademark->id }}')">

                                        <button type="submit" class="btn icon btn-sm btn-primary"
                                                data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                                data-bs-original-title="Setuju">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <button wire:click="change_property('{{ $trademark->id }}', 'revision')"
                                            type="button"
                                            class="btn icon btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#statusRevision"
                                            data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="Revisi">
                                        <i class="bi bi-exclamation-circle"></i>
                                    </button>
                                    <button wire:click="change_property('{{ $trademark->id }}', 'rejected')"
                                            type="button"
                                            class="btn icon btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#statusRejected"
                                            data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="Tolak">
                                        <i class="bi bi-dash-circle"></i>
                                    </button>

                                </div>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    <a href="{{ route('trademark.show', ['trademark' => $trademark->id]) }}"
                                       class="btn icon btn-sm btn-primary"
                                       data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                       data-bs-original-title="Detail"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('trademark.edit', ['trademark' => $trademark->id]) }}"
                                       class="btn icon btn-sm btn-primary"
                                       data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                       data-bs-original-title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button wire:click="destroy({{ $trademark->id }})"
                                            class="btn icon btn-sm btn-primary"
                                            data-bs-toggle-tooltip="tooltip" data-bs-placement="bottom"
                                            data-bs-original-title="Hapus">
                                        <i class="bi bi-x-lg"></i></button>
                                </div>
                            </td>

                        </tr>
                    </div>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!--status revision Modal -->
    <div class="modal modal-borderless fade text-left" id="statusRevision" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
             role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Verifikasi Permohonan </h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form wire:submit="verif">
                    <div class="modal-body">
                        <textarea wire:model="comment" name="comment" id="comment"
                                  class="form-control @error('comment') is-invalid @enderror"
                                  placeholder="Tuliskan alasan" rows="3" required></textarea>
                        <x-maz-input-error error="comment"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                                data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Revisi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--status rejected Modal -->
    <div class="modal modal-borderless fade text-left" id="statusRejected" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
             role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Verifikasi Permohonan </h4>
                    <button type="button" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                        <i data-feather="x"></i>
                    </button>
                </div>
                <form wire:submit="verif">
                    <div class="modal-body">
                        <textarea wire:model="comment" name="comment" id="comment"
                                  class="form-control @error('comment') is-invalid @enderror"
                                  placeholder="Tuliskan alasan" rows="3" required></textarea>
                        <x-maz-input-error error="comment"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary"
                                data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Close</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">Tolak</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        // If you want to use tooltips in your project, we suggest initializing them globally
        // instead of a "per-page" level.
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle-tooltip="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
    </script>
</div>
