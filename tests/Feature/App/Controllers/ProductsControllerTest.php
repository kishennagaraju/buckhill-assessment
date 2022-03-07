<?php

namespace Tests\Feature\App\Controllers;

use App\Models\Products;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\BuckhillBaseTesting;

class ProductsControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_products()
    {
        $this->get('/api/v1/products');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(1, $response['current_page']);
        $this->assertEquals(env('PAGINATION_LIMIT', 10), count($response['data']));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_products_with_page()
    {
        $this->get('/api/v1/products?page=2');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_products_with_all_query_params()
    {
        $this->get('/api/v1/products?page=2&limit=5&sortBy=id&desc=1');

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
        $this->assertEquals(5, count($response['data']));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_brands_with_env_pagination_limit()
    {
        putenv('PAGINATION_LIMIT=5');
        $this->get('/api/v1/products');

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertEquals(1, $response['current_page']);
        $this->assertEquals(5, count($response['data']));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_product_success()
    {
        $this->get('/api/v1/products?limit=1');

        $response = $this->decodeResponseJson();

        $product = $response['data'][0];

        $this->get('/api/v1/products/' . $product['uuid']);

        $this->assertResponseStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_product_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/products/42342342');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_product_success()
    {
        $this->get('/api/v1/categories?limit=1');
        $category = $this->decodeResponseJson();
        $product = [
            'category_uuid' => $category['data'][0]['uuid'],
            'title' => 'Test Feature Testing Product',
            'price' => 100.00,
            'description' => 'Test Product for feature testing',
            'metadata' => [
                'brand' => Str::uuid(),
                'image' => Str::uuid(),
            ]
        ];
        $this->post('/api/v1/products', $product, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('products', [
            'title' => $product['title'],
            'price' => $product['price'],
            'description' => $product['description']
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_product_failure_unauthorized()
    {
        $this->get('/api/v1/categories?limit=1');
        $category = $this->decodeResponseJson();
        $product = [
            'category_uuid' => $category['data'][0]['uuid'],
            'title' => 'Test Feature Testing Product',
            'price' => 100.00,
            'description' => 'Test Product for feature testing',
            'metadata' => [
                'brand' => Str::uuid(),
                'image' => Str::uuid(),
            ]
        ];
        $this->post('/api/v1/products', $product);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseMissing('products', [
            'title' => $product['title'],
            'price' => $product['price'],
            'description' => $product['description']
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_product_failure_invalid_data()
    {
        $this->expectException(ValidationException::class);
        $this->get('/api/v1/categories?limit=1');
        $category = $this->decodeResponseJson();
        $product = [
            'title' => 'Test Feature Testing Product',
            'price' => 100.00,
            'description' => 'Test Product for feature testing',
            'metadata' => [
                'brand' => Str::uuid(),
                'image' => Str::uuid(),
            ]
        ];
        $this->post('/api/v1/products', $product, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_product_success()
    {
        $product = Products::query()->first();
        $this->put('/api/v1/products/' . $product->uuid, [
            'category_uuid' => $product->category_uuid,
            'uuid' => Str::uuid(),
            'title' => 'Test Update Product',
            'description' => 'Test Update Product Description',
            'price' => '101.00',
            'metadata' => $product->metadata,
        ], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('products', ['title' => 'Test Update Product']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_product_failure_unauthorized()
    {
        $product = Products::query()->first();
        $this->put('/api/v1/products/' . $product->uuid, [
            'category_uuid' => $product->category_uuid,
            'uuid' => Str::uuid(),
            'title' => 'Test Update Product',
            'description' => 'Test Update Product Description',
            'price' => '101.00',
            'metadata' => $product->metadata,
        ]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseMissing('products', ['title' => 'Test Update Product']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_product_failure_invalid_data()
    {
        $this->expectException(ValidationException::class);
        $product = Products::query()->first();
        $this->put('/api/v1/products/' . $product->uuid, [
            'title' => 'Test Update Product',
            'description' => 'Test Update Product Description',
            'price' => '101.00',
            'metadata' => $product->metadata,
        ], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_product_failure_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $product = Products::query()->first();
        $this->put('/api/v1/products/1234567', [
            'category_uuid' => $product->category_uuid,
            'uuid' => Str::uuid(),
            'title' => 'Test Update Product',
            'description' => 'Test Update Product Description',
            'price' => '101.00',
            'metadata' => $product->metadata,
        ], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_product_success()
    {
        $product = Products::query()->first();
        $this->delete('/api/v1/products/' . $product->uuid . '?token='. $this->getAuthTokenForAdmin());

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseMissing('products', ['title' => $product->title, 'deleted_at' => null]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_product_unauthorized()
    {
        $product = Products::query()->first();
        $this->delete('/api/v1/products/' . $product->uuid);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_product_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->delete('/api/v1/products/123455?token=' . $this->getAuthTokenForAdmin());
    }
}
