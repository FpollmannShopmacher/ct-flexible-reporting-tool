<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use stdClass;

class StatisticsService
{
    private CommerceToolsDataFetcher $commerceToolsDataFetcher;
    private DateService $dateService;

    private CsvExportService $csvExportService;

    public function __construct(CommerceToolsDataFetcher $commerceToolsDataFetcher, DateService $dateService, CsvExportService $csvExportService)
    {
        $this->commerceToolsDataFetcher = $commerceToolsDataFetcher;
        $this->dateService = $dateService;
        $this->csvExportService = $csvExportService;
    }

    public function fetchStatistic(Request $request): array
    {
        $urlParams = $request->query();
        $mappedParamsArr = $this->mapRequestParams($urlParams);

        $selectedPeriods = get_object_vars($mappedParamsArr['period']);
        $selectedData = get_object_vars($mappedParamsArr['data']);

        $orderCounts = [];
        $orderSales = [];

        if (isset($selectedData['order-count'])) {
            $orderCounts = $this->fetchOrderCountsByPeriod($selectedPeriods);
        }

        if (isset($selectedData['order-sales'])) {
            $orderSales = $this->fetchOrderSalesByPeriod($selectedPeriods);
        }

        return ['orderSales' => $orderSales, 'orderCounts' => $orderCounts];
    }

    private function mapRequestParams(array $input): array
    {
        $result = ['period' => new stdClass(), 'data' => new stdClass(),];

        foreach ($input as $key => $value) {
            if (str_starts_with($key, 'period-')) {
                $periodKey = str_replace('period-', '', $key);
                $result['period']->$periodKey = $value === 'on';
            } elseif (str_starts_with($key, 'data-')) {
                $dataKey = str_replace('data-', '', $key);
                $result['data']->$dataKey = $value === 'on';
            }
        }

        return $result;
    }
    private function fetchOrderCountsByPeriod($selectedPeriods): array
    {
        $orderCounts = [];
        foreach (array_keys($selectedPeriods) as $period) {
            $dateParam = $this->dateService->getDateParam($period);
            $count = $this->commerceToolsDataFetcher->getCurrentOrderCount('createdAt > "' . $dateParam . '"');
            $orderCounts[$period] = $count;
        }
        return $orderCounts;
    }
    private function fetchOrderSalesByPeriod($selectedPeriods): array
    {
        $orderSales = [];

        foreach (array_keys($selectedPeriods) as $period) {
            $dateParam = $this->dateService->getDateParam($period);
            $orderArr = $this->commerceToolsDataFetcher->fetchAllOrders('createdAt > "' . $dateParam . '"');
            $orderSales[$period] = number_format($this->getOrderBatchSales($orderArr) / 100, 2, ',', '.') . '€';
        }

        return $orderSales;
    }
    private function getOrderBatchSales($orderBatchArr)
    {
        $sales = 0;
        foreach ($orderBatchArr as $order) {
            $sales += $order->totalPrice->centAmount;
        }
        return $sales;
    }


    public function downloadStatistics(Request $request)
    {
        $requestData = $request->query('btn', '');
        $jsonString = html_entity_decode($requestData);
        $jsonObjects = '[' . str_replace('}{', '},{', $jsonString) . ']';

        return $this->csvExportService->convertJsonToCsv($jsonObjects);
    }
}
