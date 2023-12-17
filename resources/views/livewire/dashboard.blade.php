<?php

use App\Models\LoginLog;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.admin')]
class extends Component {
    // default year now
    public $year = null;

    public function mount()
    {
        $this->year = date('Y');

        $this->dispatchLoginLog($this->year);
    }

    public function with()
    {
        return [
            // login dalam satu hari, bulan, tahun
            'loginYear' => LoginLog::whereYear('created_at', date('Y'))->get()->count(),
            'loginMonth' => LoginLog::whereMonth('created_at', date('m'))->get()->count(),
            'loginDay' => LoginLog::whereDay('created_at', date('d'))->get()->count(),
        ];
    }

    public function dispatchLoginLog($year)
    {
        $loginLogs = LoginLog::whereYear('created_at', $year)->get();

        $monthlyCounts = array_fill(1, 12, 0);

        $loginCount = $loginLogs->groupBy(function ($item) {
            return $item->created_at->format('n'); // 'n' untuk representasi numerik bulan
        })->map(function ($item) {
            return $item->count();
        })->toArray();

        foreach ($loginCount as $month => $count) {
            $monthlyCounts[$month] = $count;
        }

        $monthlyValues = array_values($monthlyCounts);

        $this->dispatch('dataLogin', $monthlyValues);

    }


}; ?>

<div>
    <x-slot:title>Dashboard</x-slot:title>

    <section class="row">
        <div class="col-12 col-lg-9 order-1 order-lg-0">

{{--            Chart start here --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Akses</h4>
                        </div>
                        <div class="card-body">

                            <div id="login-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 order-0 order-lg-1">
            <div class="row flex-lg-column">
                <div class="col-6 col-lg-12 col-md-4">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="row">
                                <div class="d-flex flex-column flex-md-row justify-content-start gap-3">
                                    <div class="stats-icon purple mb-2 col-md-4">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Hari Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $loginDay }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-12 col-md-4">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="row">
                                <div class="d-flex flex-column flex-md-row justify-content-start gap-3">
                                    <div class="stats-icon purple">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Bulan Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $loginMonth }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-12 col-md-4">
                    <div class="card">
                        <div class="card-body px-4 py-4">
                            <div class="row">
                                <div class="d-flex justify-content-start gap-3">
                                    <div class="stats-icon purple mb-2 col-4">
                                        <i class="iconly-boldShow"></i>
                                    </div>
                                    <div class="col-8">
                                        <h6 class="text-muted font-semibold">Tahun Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $loginYear }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
