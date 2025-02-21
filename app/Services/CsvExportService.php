<?php

namespace App\Services;

use Illuminate\Support\Facades\Response;

class CsvExportService
{
    public function convertJsonToCsv($jsonData)
    {
        $data = json_decode($jsonData, true);

        if (empty($data)) {
            return response()->json(['error' => 'No data to export'], 400);
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        );

        $callback = function () use ($data) {
            $header = ['key', 'day', 'week', 'month', 'year'];

            $file = fopen('php://output', 'w');
            fputcsv($file, $header, ",", '"', "\n");

            for ($i = 0; $i < count($data); $i++) {
                $key = array_keys($data)[$i];
                $values = $data[$key];
                fputcsv($file, array_merge([$key], $values), ",", '"', "\n");

            }
            fflush($file);
            fpassthru($file);
            fclose($file);
        };

        return response()->streamDownload($callback, 'orders.csv', $headers);
    }
}
