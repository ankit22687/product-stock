<?php

namespace Tests\Feature;

use Tests\TestCase;
use Artisan;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class StockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
        // $this->loginWithFakeUser();
    }

    /** @test */
    public function test_stock_added_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $oProduct = factory(Product::class)->create([
            "code" => "12345",
            "name" => "Test",
            "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ]);

        $stockData = [
            'product_code' => '12345',
            'on_hand' => 1,
            'production_date' => Carbon::now()->format('d/m/y')
        ];

        $response = $this->json('POST', 'api/add-stock', $stockData, ['Accept' => 'application/json','Content-Type'=> 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "success",
                "data" => [
                    "stock" => [
                        "product_id",
                        "on_hand",
                        "production_date",
                        "created_at",
                        "updated_at"
                    ],
                ],
                "message"
            ]);

        $result = json_decode($response->getContent());

        $this->assertEquals($oProduct->id, $result->data->stock->product_id);
        $this->assertEquals(1, $result->data->stock->on_hand);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_add_product_without_logging_in()
    {
        $stockData = [
            'product_code' => '12345',
            'on_hand' => 1,
            'production_date' => Carbon::now()->format('m/d/y')
        ];

        $response = $this->json('POST', '/api/add-stock', $stockData, ['Accept' => 'application/json']);

        $response->assertStatus(401);
    }

    /** @test */
    public function validate_invalid_stock_csv_file()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
 
        $filePath = resource_path("csvs/primex-stock-invalid.csv");
 
        $response = $this->postJson('/api/import-stock', [
             'csv_file' => new UploadedFile($filePath, 'primex-stock-invalid.csv', null, null, true, true),
         ])->assertStatus(422)
         ->assertJson([
             "success" => false,
             "message" => "Not able to import Stock",
             "data" => [
                 "error" => [
                     "product_code.1" => [
                         "The product_code.1 field is required."
                     ],
                     "on_hand.2" => [
                         "The on_hand.2 field is required."
                     ],
                     "on_hand.3" => [
                         "The on_hand.3 field is required."
                     ],
                     "production_date.1" => [
                         "The production_date.1 does not match the format d/m/y."
                     ],
                     "production_date.3" => [
                         "The production_date.3 field is required."
                     ]
                 ]
             ]
         ]);
    }

    /** @test */
    public function user_should_be_able_to_upload_stock_csv_file()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $filePath = resource_path("csvs/primex-products-test.csv");

        $response = $this->postJson('/api/import-products', [
            'csv_file' => new UploadedFile($filePath, 'primex-product-test.csv', null, null, true, true),
        ])->assertStatus(201);

        $result = json_decode($response->getContent());
 
        $this->assertEquals('Products imported successfully.', $result->message);

        $stockFilePath = resource_path("csvs/primex-stock-test.csv");

        $stockResponse = $this->postJson('/api/import-stock', [
            'csv_file' => new UploadedFile($stockFilePath, 'primex-stock-test.csv', null, null, true, true),
        ])->assertStatus(201);

        $stockResult = json_decode($stockResponse->getContent());
 
        $this->assertEquals('Stock imported successfully.', $stockResult->message);
    }
}
