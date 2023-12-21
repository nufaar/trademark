<?php

use App\Models\LoginLog;
use App\Models\Trademark;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('layouts.admin')]
class extends Component {
    // default year now
    public $year = null;
    public $chart = 1;
    public $trademarks;


    public function updatedChart($value)
    {
        $this->chart = $value;
        $data = $this->getTrademarksCount($value);

        $this->dispatch('update-chart-permohonan', $data);
    }

    public function mount()
    {
        $this->year = date('Y');
        $this->trademarks = Trademark::query()
            ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->get();

        $this->dispatch('dataLogin', $this->getLoginLog($this->year));
        $this->dispatch('dataPermohonan', $this->getTrademarksCount($this->year));
        $this->dispatch('dataTrademarkStatus', $this->countTrademarkStatus());
    }

    public function with()
    {
        return [
            // login dalam satu hari, bulan, tahun
            'loginYear' => LoginLog::whereYear('created_at', date('Y'))
                ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->get()->count(),
            'loginMonth' => LoginLog::whereMonth('created_at', date('m'))
                ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->get()->count(),
            'loginDay' => LoginLog::whereDay('created_at', date('d'))
                ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->get()->count(),
            // permohonan dalam satu hari, bulan, tahun
            'permohonanYear' => Trademark::whereYear('created_at', date('Y'))
                ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->get()->count(),
            'permohonanMonth' => Trademark::whereMonth('created_at', date('m'))
                ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->get()->count(),
            'permohonanDay' => Trademark::whereDay('created_at', date('d'))
                ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
                ->get()->count(),
        ];
    }

    public function getLoginLog($year)
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

        return $monthlyValues;

    }


    public function getTrademarksCount($year)
    {
        $permohonanLogs = Trademark::whereYear('created_at', $year)
            ->when(auth()->user()->hasRole('pemohon'), function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->get();

        return $this->getDataYearly($permohonanLogs);
    }

    public function getDataYearly($data)
    {
        $monthlyCounts = array_fill(1, 12, 0);

        $dataCount = $data->groupBy(function ($item) {
            return $item->created_at->format('n'); // 'n' untuk representasi numerik bulan
        })->map(function ($item) {
            return $item->count();
        })->toArray();

        foreach ($dataCount as $month => $count) {
            $monthlyCounts[$month] = $count;
        }

        $monthlyValues = array_values($monthlyCounts);

        return $monthlyValues;
    }

    public function countTrademarkStatus()
    {
//        $trademarks = Trademark::all();
        // check if auth  ser has role name 'pemohon' filter
        if (auth()->user()->hasRole('pemohon')) {
            $trademarks = Trademark::where('user_id', auth()->user()->id)->get();
        } else {
            $trademarks = Trademark::all();
        }

        $status = $trademarks->groupBy('status')->map(function ($item) {
            return $item->count();
        })->toArray();

        return $status;
    }

}; ?>

<div>
    <x-slot:title>Dashboard</x-slot:title>

    <h5 class="mb-6 mt-8">Permohonan</h5>

    <section class="row">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="d-flex justify-content-start gap-3">
                                    <div class="stats-icon blue col-4">
                                        <i class="iconly-boldGraph"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Hari Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $permohonanDay }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="d-flex justify-content-start gap-3">
                                    <div class="stats-icon purple col-4">
                                        <i class="iconly-boldGraph"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Bulan Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $permohonanMonth }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="d-flex justify-content-start gap-3">
                                    <div class="stats-icon red  col-4">
                                        <i class="iconly-boldGraph"></i>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Tahun Ini</h6>
                                        <h6 class="font-extrabold mb-0">{{ $permohonanYear }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="row">
        <div class="col-12 col-md-7">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-3" wire:ignore>
                            <div class="d-flex justify-content-between">
                                <h5>Permohonan dalam setahun</h5>
                                <div>
                                    <select class="form-select" wire:model.live.debounce="chart">
                                        <option value="2023">2023</option>
                                        <option value="2022">2022</option>
                                        <option value="2021">2021</option>
                                    </select>
                                </div>
                            </div>
                            <div id="chart-permohonan"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body py-6">
                            <h5 class="mb-4">Status Permohonan</h5>
                            <div id="trademark-status-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @can('verify trademark')
        <section class="row">
            <h5 class="mt-4 mb-3">Akses Pengguna</h5>
            <div class="col-12 col-lg-9 order-1 order-lg-0">
                {{--            Chart start here --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
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
                                        <div class="stats-icon purple col-md-4">
                                            <i class="iconly-boldGraph"></i>
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
                                        <div class="stats-icon blue col-4">
                                            <i class="iconly-boldGraph"></i>
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
                                        <div class="stats-icon red col-4">
                                            <i class="iconly-boldGraph"></i>
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
    @endcan

    <section class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-responsive">
                    <h5>Daftar Permohonan</h5>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nama Merek</th>
                            <th>Tanggal Diajukan</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trademarks as $trademark)
                            <tr>
                                <td>{{ $trademark->name }}</td>
                                <td>{{ $trademark->created_at->format('d M Y') }}</td>
                                <td><span class="badge rounded-pill bg-light-{{ config('constants.status.color.' . $trademark->status) }}">{{ config('constants.status.text.' . $trademark->status) }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    @push('script')
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('dataPermohonan', (dataPermohonan) => {
                    let optionsPermohonan = {
                        annotations: {
                            position: "back",
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        chart: {
                            type: "bar",
                            height: 300,
                        },
                        fill: {
                            opacity: 1,
                        },
                        plotOptions: {},
                        series: [
                            {
                                name: "Akses",
                                data: dataPermohonan[0]
                            },
                        ],
                        colors: "#435ebe",
                        xaxis: {
                            categories: [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "May",
                                "Jun",
                                "Jul",
                                "Aug",
                                "Sep",
                                "Oct",
                                "Nov",
                                "Dec",
                            ],
                        },
                    }


                    var chart = new ApexCharts(document.querySelector("#chart-permohonan"), optionsPermohonan);

                    chart.render();


                    Livewire.on('update-chart-permohonan', (data) => {

                        console.log(data[0]);
                        chart.updateSeries([{
                            data: data[0]
                        }])
                    })
                })
                Livewire.on('dataTrademarkStatus', (dataTrademarkStatus) => {
                    let status = Object.keys(dataTrademarkStatus[0]);
                    let count = Object.values(dataTrademarkStatus[0]);

                    const statusMapping = {
                        "approved": "Diterima",
                        "revision": "Revisi",
                        "rejected": "Ditolak",
                        "pending": "Diajukan"
                    };

                    status = status.map(status => statusMapping[status]);

                    let optionsTrademarkStatus = {
                        series: count,
                        chart: {
                            type: 'donut',
                        },
                        labels: status,
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '22px',
                                            fontFamily: 'Helvetica, Arial, sans-serif',
                                            fontWeight: 600,
                                            color: undefined,
                                            offsetY: -10
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '22px',
                                            fontFamily: 'Helvetica, Arial, sans-serif',
                                            fontWeight: 600,
                                            color: '#777',
                                            offsetY: 0,
                                            formatter: function (val) {
                                                return val
                                            }
                                        },
                                        total: {
                                            show: true,
                                            showAlways: true,
                                            label: 'Total',
                                            fontSize: '16px',
                                            fontFamily: 'Helvetica, Arial, sans-serif',
                                            fontWeight: 400,
                                            color: '#777',
                                            formatter: function (w) {
                                                return w.globals.seriesTotals.reduce((a, b) => {
                                                    return a + b
                                                }, 0)
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#trademark-status-chart"), optionsTrademarkStatus);
                    chart.render();
                })
            })
        </script>
    @endpush
</div>
