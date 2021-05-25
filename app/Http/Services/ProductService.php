<?php

namespace App\Http\Services;

use App\Models\Product;
use Illuminate\Support\Arr;
use App\Http\Traits\CsvHelper;
use Carbon\Carbon;
use Illuminate\Support\Collection;

use Auth;

class ProductService
{
    use CsvHelper;
    /**
     * Add Product
     * @param $productData
     * @throws \Exception
     * @return Product
     */
    public function addProduct($productData): Product
    {
        return  Product::create([
            'code' => Arr::get($productData, 'code'),
            'name' => Arr::get($productData, 'name'),
            'description' => Arr::get($productData, 'description')
        ]);
    }

    /**
     * get all Products
     * @param $filter
     * @throws \Exception
     * @return Products[]
     */
    public function getAllProducts($filter = [])
    {
        $productsQuery = Product::Query();

        if (Arr::get($filter, 'with_stock_quantity') != '' || Arr::get($filter, 'stock_sort_by') != '' || Arr::get($filter, 'stock_only') != '') {
            $productsQuery->leftjoin('stock', 'products.id', '=', 'stock.product_id')
            ->addSelect([ 'products.*','stock.*','stock.id as stock_id',\DB::raw('sum(on_hand) as stock_quantity')])
            ->groupBy('products.id');
        }

        if (Arr::get($filter, 'stock_sort_by') != '') {
            $productsQuery->orderByStockQuantity(Arr::get($filter, 'stock_sort_by'));
        }

        if (Arr::get($filter, 'stock_only') != '' && Arr::get($filter, 'stock_only') == 'yes') {
            $productsQuery->productsWithStockOnly();
        }
        
        return $productsQuery->get();
    }

    /**
     * Add Product
     * @param $productData
     * @param $productId
     * @throws \Exception
     * @return Product
     */
    public function updateProduct($productData, $productId)
    {
        Product::where('id', $productId)
        ->update([
            'code' => Arr::get($productData, 'code'),
            'name' => Arr::get($productData, 'name'),
            'description' => Arr::get($productData, 'description')
        ]);
        return $this->getProduct($productId);
    }

    /**
     * get Product
     * @param $productId
     * @param $withStock
     * @return Product
     */
    public function getProduct($productId, $withStock = ''): Product
    {
        $productQuery = Product::where('products.id', $productId);

        if ($withStock != '' && $withStock === 'yes') {
            $productQuery->leftjoin('stock', 'products.id', '=', 'stock.product_id')
            ->addSelect(['products.*','stock.*','stock.id as stock_id',\DB::raw('sum(on_hand) as stock_quantity')]);
        }

        return $productQuery->first();
    }

    /** Delete Product
     * @param integer $productId
     * @return Void
     */
    public function deleteProduct($productId)
    {
        Product::where('id', $productId)->delete();
    }

    /**
     * import product csv
     * @param $filePath
     * @throws \Exception
     * @return Void
     */
    public function importProductCSV($filePath)
    {
        $data = array_map('str_getcsv', file($filePath));
        
        $header = array_shift($data);

        $csvHeader = $this->removeSpecialCharacter($header);
        
        $csvData = $this->mergeHeaderWithRows($data, $csvHeader);
        
        // create collection from array and create product if code is not exist otherwise update the product
        collect($csvData)
            ->map(function (array $row) {
                return Arr::only($row, ['code', 'name', 'description']);
            })
            ->chunk(500)
            ->each(function (Collection $chunk) {
                Product::upsert($chunk->all(), 'code');
            });
    }
}
