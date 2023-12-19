<?php

use App\Models\Trademark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('front.index')]
class extends Component {
    public $keyword = '';
    public $status;

    public function with()
    {
        return [
            'trademarks' => Trademark::query()
                ->when($this->keyword, function ($query) {
                    return scopeSearch($query, $this->keyword, ['name']);
                })
                ->when($this->status, function ($query) {
                    return $query->where('status', $this->status);
                })
                ->orderBy('created_at', 'desc')
                ->get()
        ];

    }
}; ?>

<div>
    <p class="font-semibold text-xl mb-4 text-center mt-6">Rekap Data Permohonan</p>
    <div class="flex w-full justify-center mb-6 gap-3">
        <input wire:model.live.debounce.300ms="keyword" type="text" placeholder="Cari..." class="input w-full max-w-xs input-bordered"/>
        <select wire:model.live.debounce="status" class="select select-bordered">
            <option value="">Semua</option>
            <option value="approved">Diterima</option>
            <option value="revision">Perlu Perbaikan</option>
            <option value="rejected">Ditolak</option>
            <option value="pending">Diajukan</option>
        </select>
    </div>

    <div class="mt-6 shadow-md p-3 w-full flex flex-wrap">
        @foreach($trademarks as $trademark)
            <div class="basis-1/2 md:basis-1/3 lg:basis-1/4 p-1">
                <div class="flex p-2 gap-4 items-center rounded border border-base-300">
                    <div
                        class="w-24 h-24 bg-white flex items-center justify-center rounded border border-base-200 p-2">
                        <img src="{{ asset('storage/logos/' . $trademark->logo) }}">
                    </div>
                    <div>
                        <div class="font-semibold">{{ $trademark->name }}</div>
                        <div>
                            <p class="badge badge-{{ config('constants.status.color.daisy.' . $trademark->status)  }}">{{ config('constants.status.text.' . $trademark->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
