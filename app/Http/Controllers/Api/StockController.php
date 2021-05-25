<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StockRequest;
use App\Http\Services\StockService;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StockImport;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Http\ImportValidator\StockImportValidator;
use App;

class StockController extends BaseController
{

    /**
     * StockController constructor.
     * @param StockService $stockService
     */
    public function __construct(
        StockService $stockService
    ) {
        $this->stockService = $stockService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addStock(StockRequest $request)
    {
        DB::beginTransaction();
        try {
            $oStock = $this->stockService->addStock($request->all());
            DB::commit();
            return $this->sendSuccessResponse(['stock' => $oStock], 'Stock Added successfully.', 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendErrorResponse('Not able to create product', ['error'=>$exception->getMessage()]);
        }
    }

    /**
     * parse stock import
     */
    public function importStock(Request $request)
    {
        ini_set('max_execution_time', 60000);
        DB::beginTransaction();
        try {
            // Create validator object
            $validator = App::make('App\Http\ImportValidator\StockImportValidator');

            // Run validation with input file
            $validator = $validator->validate($request->file('csv_file'));

            if ($validator->fails()) {
                return $this->sendErrorResponse('Not able to import Stock', ['error'=>$validator->errors()], 422);
            }

            $result = $this->stockService->importStockCSV($request->csv_file->getRealPath());
            DB::commit();
            return $this->sendSuccessResponse([], 'Stock imported successfully.', 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendErrorResponse('Import failed', ['error'=>'Not able to import stock'], 422);
        }
    }
}
