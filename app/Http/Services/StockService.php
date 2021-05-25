<?php

namespace App\Http\Services;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Http\Traits\CsvHelper;

class StockService
{
    use CsvHelper;

    /**
     * Add Stock For product
     * @param $stockData
     * @throws \Exception
     * @return Stock
     */
    public function addStock($stockData): Stock
    {
        $oProduct = Product::where('code', Arr::get($stockData, 'product_code'))->first();
        return Stock::create([
            'product_id' => $oProduct->id,
            'on_hand'=> Arr::get($stockData, 'on_hand'),
            'production_date'=>Carbon::createFromFormat('d/m/y', Arr::get($stockData, 'production_date'))->format('Y-m-d'),
        ]);
    }

    /**
     * import stock csv
     * @param $filePath
     * @throws \Exception
     * @return Void
     */
    public function importStockCSV($filePath)
    {
        $data = array_map('str_getcsv', file($filePath));

        $header = array_shift($data);
        $csvHeader = $this->removeSpecialCharacter($header);
        
        $csvData = $this->mergeHeaderWithRows($data, $csvHeader);
    
        $result = $this->prepareDataBeforeImport($csvData);
        
        $chunks = array_chunk($result, 500);
        foreach ($chunks as $chunk) {
            Stock::insert($chunk);
        }
    }

    /**
     * prepare data before import
     * @param $csvData
     * @return array
     */
    public function prepareDataBeforeImport(array $csvData): array
    {
        $j = 0;
        foreach ($csvData as $csvRow) {
            $productId = Product::where('code', $csvRow['product_code'])->pluck('id')->first();
            if ($productId != '') {
                $result[$j]['product_id'] = $productId;
                $result[$j]['on_hand'] = $csvRow['on_hand'];
                $result[$j]['production_date'] = Carbon::createFromFormat('d/m/y', Arr::get($csvRow, 'production_date'))->format('Y-m-d');
                $result[$j]['created_at'] = Carbon::now();
                $result[$j]['updated_at'] = Carbon::now();
            }
            $j++;
        }
        return $result;
    }
}
