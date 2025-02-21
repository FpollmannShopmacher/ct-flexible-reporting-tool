<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CommerceToolsDataFetcher;
use App\Services\StatisticsService;

class StatisticsController extends Controller
{
    private StatisticsService $statisticsService;

    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function viewStatistic(Request $request)
    {
        $data = $this->statisticsService->fetchStatistic($request);
        return view('statistics', $data);
    }

    public function download(Request $request)
    {
        return $this->statisticsService->downloadStatistics($request);
    }
}
