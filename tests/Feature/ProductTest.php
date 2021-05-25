<?php

namespace Tests\Feature;

use Tests\TestCase;
use Artisan;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('passport:install');
    }

    /** @test */
    public function test_product_added_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        
        $productData = [
            "code" => "12345",
            "name" => "Test",
            "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ];

        $response = $this->json('POST', 'api/products', $productData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "success",
                "data" => [
                    "product" => [
                        "name",
                        "code",
                        "description",
                        "created_at",
                        "updated_at"
                    ],
                ],
                "message"
            ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('12345', $result->data->product->code);
        $this->assertEquals('Test', $result->data->product->name);
        $this->assertEquals('Lorem ipsum Lorem ipsum Lorem ipsum', $result->data->product->description);
    }

    /** @test */
    public function it_returns_an_unauthorized_error_when_trying_to_add_product_without_logging_in()
    {
        $productData = [
            "code" => "12345",
            "name" => "Test",
            "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ];

        $response = $this->json('POST', '/api/products', $productData);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_product_updated_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
 
        $product = factory(Product::class)->create([
            "code" => "12345",
             "name" => "Test",
             "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ]);

        $productData = [
             "code" => "123456",
             "name" => "Test updated",
             "description" => "Lorem ipsum Lorem ipsum Lorem ipsum updated",
         ];
 
        $response = $this->json('PATCH', 'api/products/'.$product->id, $productData, ['Accept' => 'application/json'])
             ->assertStatus(200)
             ->assertJsonStructure([
                 "success",
                 "data" => [
                     "product" => [
                         "name",
                         "code",
                         "description",
                         "created_at",
                         "updated_at"
                     ],
                 ],
                 "message"
             ]);
 
        $result = json_decode($response->getContent());
 
        $this->assertEquals('123456', $result->data->product->code);
        $this->assertEquals('Test updated', $result->data->product->name);
        $this->assertEquals('Lorem ipsum Lorem ipsum Lorem ipsum updated', $result->data->product->description);
    }

    /** @test */
    public function test_product_deleted_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
 
        $product = factory(Product::class)->create([
            "code" => "12345",
             "name" => "Test",
             "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ]);

        $response = $this->json('DELETE', 'api/products/'.$product->id, [], ['Accept' => 'application/json'])
        ->assertStatus(200)
        ->assertJsonStructure([
            "success",
            "data" => [
            ],
            "message"
        ]);

        $result = json_decode($response->getContent());

        $this->assertEquals('Product Deleted successfully.', $result->message);
    }

    /** @test */
    public function test_products_listed_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        factory(Product::class)->create([
            "code" => "12345",
            "name" => "Test",
            "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ]);

        factory(Product::class)->create([
            "code" => "67890",
            "name" => "tset",
            "description" => "ipsum Lorem ipsum Lorem ipsum Lorem",
        ]);

        $products = Product::get()->toArray();
        $this->assertCount(2, $products);
        
        $response = $this->json('GET', 'api/products', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    /** @test */
    public function test_products_listed_with_stock_successfully()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $product = factory(Product::class)->create([
            "code" => "12345",
            "name" => "Test",
            "description" => "Lorem ipsum Lorem ipsum Lorem ipsum",
        ]);

        factory(Product::class)->create([
            "code" => "67890",
            "name" => "tset",
            "description" => "ipsum Lorem ipsum Lorem ipsum Lorem",
        ]);

        $products = Product::get()->toArray();
        $this->assertCount(2, $products);
        
        $response = $this->json('GET', 'api/products', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }


    /** @test */
    public function user_should_not_to_upload_pdf_file()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $filePath = resource_path("pdfs/sample.pdf");

        $response = $this->postJson('/api/import-products', [
            'csv_file' => new UploadedFile($filePath, 'sample.pdf', null, null, true, true),
            ])->assertStatus(422)
            ->assertJson([
                "success"=> false,
                "message"=> "Failed to validate data",
                "data" => [
                    "csv_file" => [
                        "Please upload csv file."
                    ]
                ]
            ]);
    }

    /** @test */
    public function validate_invalid_product_csv_file()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $filePath = resource_path("csvs/primex-products-invalid.csv");

        $response = $this->postJson('/api/import-products', [
            'csv_file' => new UploadedFile($filePath, 'primex-product-invalid.csv', null, null, true, true),
        ])->assertStatus(422)
        ->assertJson([
            "success" => false,
            "message" => "Not able to import products",
            "data" => [
                "error" => [
                    "code.5" => [
                        "The code.5 field is required."
                    ],
                    "name.0" => [
                        "The name.0 field is required."
                    ],
                    "description.1" => [
                        "The description.1 field is required."
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function user_should_be_able_to_upload_product_csv_file()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $filePath = resource_path("csvs/primex-products-test.csv");

        $response = $this->postJson('/api/import-products', [
            'csv_file' => new UploadedFile($filePath, 'primex-product-test.csv', null, null, true, true),
        ])->assertStatus(201);

        $result = json_decode($response->getContent());
 
        $this->assertEquals('Products imported successfully.', $result->message);
    }
}
