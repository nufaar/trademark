<?php

namespace App\Exports;

use App\Models\Trademark;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TrademarksExport implements FromView
{

    public function __construct($search, $perPage, $status, $startDate, $endDate)
    {
        $this->search = $search;
        $this->perPage = $perPage;
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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

    public function view(): View
    {
        return view('exports.trademarks', [
            'trademarks' => Trademark::query()
                ->when($this->search, fn($query, $search) => $this->searchScope($query, $search, ['name', 'owner', 'address']))
                ->when($this->status, fn($query, $status) => $query->where('status', $status))
                ->when($this->startDate, function ($query) {
                    return $query->whereDate('created_at', '>=', $this->startDate);
                })
                ->when($this->endDate, function ($query) {
                    return $query->whereDate('created_at', '<=', $this->endDate);
                })
                ->paginate($this->perPage)
        ]);
    }
}
