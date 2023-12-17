<?php

if (! function_exists('private_key')) {
    function private_key($value) {
        $date = now()->format('Ymd');
        $combine = $date . $value . env('PDKI_SECRET_KEY');
        $key = hash('sha256', $combine);

        return $key;
    }
}

if (! function_exists('scopeSearch')) {
    function scopeSearch($query, $search, $columns = null) {
        $query->where(function ($query) use ($search, $columns) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'LIKE', "%{$search}%");
            }
        });

        return $query;
    }
}
