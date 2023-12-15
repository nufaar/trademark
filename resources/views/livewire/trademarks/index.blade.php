<?php

use App\Models\Trademark;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new
#[Layout('layouts.admin')]
class extends Component {

    use WithPagination;

    public $comment = '';
    public $status;
    public $trademark_id = 0;

    public Trademark $trademark;

    public $perPage = 5;
    public $search = '';
    public $filterStatus = '';

    // dibuat renderless agar modal tidak tertutup karena render ulang
    #[Renderless]
    public function change_property($id, $status = 'revision')
    {
        $this->trademark_id = $id;
        $this->status = $status;
    }

    public function searchScope($query, $keyword, $columns)
    {
        if ($keyword) {
            return $query->where(function ($query) use ($keyword, $columns) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%' . $keyword . '%');
                }
            });
        }

        return $query;
    }

    public function with()
    {
//        check if user is pemohon

        return [
            'trademarks' => Trademark::query()
                ->when(Auth::user()->role == 'pemohon', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->when($this->search, function ($query) {
                    return $this->searchScope($query, $this->search, ['name', 'owner']);
                })
                ->when($this->filterStatus, function ($query) {
                    return $query->where('status', $this->filterStatus);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage)
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

        $this->reset('comment');

    }

    public function verifApproved($id)
    {
        $trademark = Trademark::findOrFail($id);

        $trademark->update([
            'status' => 'approved',
            'comment' => "Permohonan disetujui",
        ]);

        $this->reset('comment');

    }
}; ?>

<div>
    <x-slot:title>
        Daftar Permohonan Merek
    </x-slot:title>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('trademark.index') }}">Permohonan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Permohonan
        </li>
    </x-slot:menu>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="d-flex gap-3">
                <input wire:model.live.debounce300ms="search" type="text" class="form-control" placeholder="Cari...">
                <select wire:model.live="filterStatus" class="form-select">
                    <option value="">Semua</option>
                    <option value="approved">Disetujui</option>
                    <option value="revision">Perbaiki</option>
                    <option value="rejected">Ditolak</option>
                    <option value="pending">Menunggu</option>
                </select>
            </div>
            <a href="{{ route('trademark.create') }}" class="btn btn-primary icon icon-left" wire:navigate><i
                    class="bi bi-person-add"></i>
                Tambah Permohonan</a>
        </div>
        <div class="card-body table-responsive">
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
                                             alt="logo Merek" width="75">
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
        <div class="d-flex justify-content-between mx-4">
            <div>
                <select wire:model.live="perPage" class="form-select">
                    <option>5</option>
                    <option>10</option>
                    <option>15</option>
                    <option>20</option>
                </select>
            </div>
            <div>{{ $trademarks->links() }}</div>
        </div>
    </div>

    <!--status revision Modal -->
    <div wire:ignore.self class="modal modal-borderless fade text-left" id="statusRevision" tabindex="-1" role="dialog"
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
    <div wire:ignore.self class="modal modal-borderless fade text-left" id="statusRejected" tabindex="-1" role="dialog"
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
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle-tooltip="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
    </script>
</div>
