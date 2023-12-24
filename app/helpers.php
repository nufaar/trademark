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

if (! function_exists('getSimilarity')) {
    function getSimilarity($trademarks, $name)
    {
        $comparison = new \Atomescrochus\StringSimilarities\Compare();
        $result = [];
        foreach ($trademarks as $trademark) {
            $result[] = [
                'name' => $trademark['_source']['nama_merek'],
                'score' => $comparison->smg(Str::lower($name), Str::lower($trademark['_source']['nama_merek'])) * 100,
                'kelas' => $trademark['_source']['t_class'][0]['class_no'],
                'status' => $trademark['_source']['status_group']['status_group'],
            ];
        }
        return $result;
    }
}
