<?php

namespace App\Http\Traits;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Arr;
use Carbon\Carbon;

trait CsvHelper
{
    /**
    * remove special character from header
    * @param $header
    * @return array
    */
    public function removeSpecialCharacter(array $header): array
    {
        foreach ($header as $item) {
            if (mb_detect_encoding($item) === 'UTF-8') {
                $csvHeader[] = preg_replace('/\x{EF}\x{BB}\x{BF}/', '', $item);
            } else {
                $csvHeader[] = $item;
            }
        }
        return $csvHeader;
    }

    /**
    * merge Header with Rows
    * @param $data
    * @param $csvHeader
    * @return array
    */
    public function mergeHeaderWithRows(array $data, array $csvHeader): array
    {
        $i = 0;
        foreach ($data as $row) {
            $csvData[$i] = array_combine($csvHeader, $row);
            $i++;
        }
        return $csvData;
    }
}
