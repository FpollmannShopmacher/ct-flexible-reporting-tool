<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\CommerceToolsDataFetcher;
use Psy\Util\Json;
use function Symfony\Component\String\b;
use function Symfony\Component\Translation\t;
use Illuminate\Support\Carbon;

class ViewController extends Controller
{

    private CommerceToolsDataFetcher $commerceToolsDataFetcher;

    public function __construct(CommerceToolsDataFetcher $commerceToolsDataFetcher)
    {
        $this->commerceToolsDataFetcher = $commerceToolsDataFetcher;
    }

    public function showOrderCount(Request $request): string
    {
        $period = $request->query('period', 'default');
        switch ($period) {
            case 'day':
                $dateParam = Carbon::now('Europe/Berlin')->subDay()->toIso8601String();
                break;
            case 'week':
                $dateParam = Carbon::now('Europe/Berlin')->subWeek()->toIso8601String();
                break;
            case 'month':
                $dateParam = Carbon::now('Europe/Berlin')->subMonth()->toIso8601String();
                break;
            case 'year':
                $dateParam = Carbon::now('Europe/Berlin')->subYear()->toIso8601String();
                break;
            default:
                $dateParam = Carbon::now('Europe/Berlin')->toIso8601String();
                break;
        }

        $orderCount = $this->commerceToolsDataFetcher->getCurrentOrderCount('createdAt > "' . $dateParam . '"');
        return view('defaultView', ['orderCount' => $orderCount, 'period' => $period]);
    }

    public function download()
    {
        $dateParam = Carbon::now('Europe/Berlin')->subMonth()->toIso8601String();
        $file = $this->commerceToolsDataFetcher->fetchCurrentOrders('version > "0"');
        $content = json_encode($file);
        return response($content)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', 'attachment; filename="orders.json"');
    }


}
