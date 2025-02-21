<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class DateService
{
    public function getDateParam(string $period): string
    {
        $now = Carbon::now('Europe/Berlin');
        $periodMap = [
            'day' => 'subDay',
            'week' => 'subWeek',
            'month' => 'subMonth',
            'year' => 'subYear',
        ];

        $method = $periodMap[$period] ?? null;
        return $method ? $now->$method()->toIso8601String() : $now->toIso8601String();
    }
}
