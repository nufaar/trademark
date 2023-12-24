<?php

use App\Exports\TrademarksExport;
use App\Models\Trademark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new
#[Layout('layouts.admin')]
class extends Component {
    use WithPagination;

    public $search = '';
    public $perPage = 5;
    public $status = '';
    public $startDate = '';
    public $endDate = '';

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
        return [
            'trademarks' => Trademark::query()
                ->when($this->search, fn($query, $search) => $this->searchScope($query, $search, ['name', 'owner', 'address']))
                ->when($this->status, function ($query) {
                    return $query->where('status', $this->status);
                })
                ->when($this->startDate, function ($query) {
                    return $query->whereDate('created_at', '>=', $this->startDate);
                })
                ->when($this->endDate, function ($query) {
                    return $query->whereDate('created_at', '<=', $this->endDate);
                })
                ->paginate($this->perPage),
        ];
    }

    public function export()
    {
        $date = date('Y-m-d');
        return Excel::download(new TrademarksExport($this->search, $this->perPage, $this->status, $this->startDate, $this->endDate), 'laporan-permohonan-' . $date . '.xlsx');
    }

}; ?>

<div>
    <x-slot name="title">Laporan</x-slot>

    <x-slot:menu>
        <li class="breadcrumb-item"><a href="{{ route('report.index') }}">Laporan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Laporan Permohonan
        </li>
    </x-slot:menu>

    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-sm-row justify-content-between gap-3">
                <div class="d-flex flex-column flex-sm-row gap-1">
                    <div>
                        <input wire:model.live.debounce300ms="search" type="text" class="form-control"
                               placeholder="Cari...">
                    </div>
                    <div>
                        <select wire:model.live="status" class="form-select">
                            <option value="">Semua</option>
                            <option value="approved">Diterima</option>
                            <option value="revision">Perbaiki</option>
                            <option value="rejected">Ditolak</option>
                            <option value="pending">Menunggu</option>
                        </select>
                    </div>

                    <div>
                        <input wire:model.live.debounce="startDate" type="date" class="form-control" max="{{ $endDate }}">
                    </div>
                    <div>
                        <input wire:model.live.debounce="endDate" type="date" class="form-control" min="{{ $startDate }}" max="{{ now()->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    @can('export report')
                        <button wire:click="export" class="btn btn-primary">Export</button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped" id="table1">
                <thead>
                <tr>
                    <th>Merek</th>
                    <th>Pemilik</th>
                    <th>Alamat</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($trademarks as $trademark)
                    <tr>
                        <td>{{ $trademark->name }}</td>
                        <td>{{ $trademark->owner }}</td>
                        <td>{{ $trademark->address }}</td>
                        <td>{{ $trademark->created_at->format('d M Y') }}</td>
                        <td>
                            <div
                                class="badge rou nded-pill bg-light-{{ config('constants.status.color.' . $trademark->status) }}">{{ config('constants.status.text.' . $trademark->status) }}</div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between mx-4 mb-4 mt-3">
            <div class="form-group">
                <select wire:model.live="perPage" class="form-select">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="{{ $trademarks->total() }}">Semua</option>
                </select>
            </div>
        </div>
    </div>
</div>
