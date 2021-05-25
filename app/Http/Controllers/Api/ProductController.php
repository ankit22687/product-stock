<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Services\ProductService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\CsvImportRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;
use Illuminate\Support\Arr;
use App;
use App\Http\ImportValidator\ProductImportValidator;

class ProductController extends BaseController
{

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $asProducts = $this->productService->getAllProducts($request);

        return $this->sendSuccessResponse(['products' => $asProducts], 'Products retrieved successfully', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $oProduct = $this->productService->addProduct($request->all());
            DB::commit();
            return $this->sendSuccessResponse(['product' => $oProduct], 'Product Added successfully.', 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendErrorResponse('Not able to create product', ['error'=>$exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $oProduct = $this->productService->getProduct($id, $request->with_stock);
        return $this->sendSuccessResponse(['product' => $oProduct], 'Product retrieved successfully.', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $existingProduct = Product::find($id);
        if (!$existingProduct instanceof Product) {
            return $this->sendErrorResponse('Invalid Request', ['error'=>'No product found']);
        }

        DB::beginTransaction();
        try {
            $oProduct = $this->productService->updateProduct($request->all(), $id);
            DB::commit();
            return $this->sendSuccessResponse(['product' => $oProduct], 'Product Updated successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendErrorResponse('Not able to update product', ['error'=>'Invalid Credentials']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingProduct = Product::find($id);
        if (!$existingProduct instanceof Product) {
            return $this->sendErrorResponse('Invalid Request', ['error'=>'No product found']);
        }

        DB::beginTransaction();
        try {
            $oProduct = $this->productService->deleteProduct($id);
            DB::commit();
            return $this->sendSuccessResponse([], 'Product Deleted successfully.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendErrorResponse('Not able to delete product', ['error'=>'Invalid Credentials']);
        }
    }

    /**
     * Import products from csv
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function importProducts(CsvImportRequest $request)
    {
        DB::beginTransaction();
        try {

            // Create validator object
            $validator = App::make('App\Http\ImportValidator\ProductImportValidator');

            // Run validation with input file
            $validator = $validator->validate($request->file('csv_file'));

            if ($validator->fails()) {
                return $this->sendErrorResponse('Not able to import products', ['error'=>$validator->errors()], 422);
            }

            $result = $this->productService->importProductCSV($request->csv_file->getRealPath());
            DB::commit();
            return $this->sendSuccessResponse([], 'Products imported successfully.', 201);
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendErrorResponse('Import failed', ['error'=>'Not able to import products'], 422);
        }
    }
}
