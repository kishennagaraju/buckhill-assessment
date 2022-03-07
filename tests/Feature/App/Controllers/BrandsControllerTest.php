<?php

namespace Tests\Feature\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class BrandsControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_brands()
    {
        $this->get('/api/v1/brands');

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
    public function test_get_all_brands_with_page()
    {
        $this->get('/api/v1/brands?page=2');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_brands_with_all_query_params()
    {
        $this->get('/api/v1/brands?page=2&limit=5&sortBy=id&desc=1');

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
        $this->get('/api/v1/brands');

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
    public function test_get_single_brand_success()
    {
        $this->get('/api/v1/brands?limit=1');

        $response = $this->decodeResponseJson();

        $blog = $response['data'][0];

        $this->get('/api/v1/brands/' . $blog['uuid']);

        $this->assertResponseStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_brand_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/brands/42342342');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_brand_success()
    {
        $data = [
            'title' => 'Test Brand'
        ];

        $this->post('/api/v1/brands', $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('brands', ['title' => $data['title']]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_brand_failure()
    {
        $data = [
            'title' => 'Test Brand'
        ];

        $this->post('/api/v1/brands', $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_brand_success()
    {
        $this->get('/api/v1/brands?limit=1');
        $response = $this->decodeResponseJson();

        $brand = $response['data'][0];
        $data = [
            'title' => 'Test Update Brand'
        ];

        $this->put('/api/v1/brands/' . $brand['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('brands', ['title' => $data['title']]);
    }

    public function test_update_brand_unauthorized()
    {
        $this->get('/api/v1/brands?limit=1');
        $response = $this->decodeResponseJson();

        $brand = $response['data'][0];
        $data = [
            'title' => 'Test Update Brand'
        ];

        $this->put('/api/v1/brands/' . $brand['uuid'], $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseMissing('brands', ['title' => $data['title']]);
    }

    public function test_update_brand_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $brand = Str::uuid();
        $data = [
            'title' => 'Test Update Brand'
        ];

        $this->put('/api/v1/brands/' . $brand, $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_brand_success()
    {
        $this->get('/api/v1/brands?limit=1');
        $response = $this->decodeResponseJson();

        $brand = $response['data'][0];
        $data = [
            'title' => 'Test Update Brand'
        ];

        $this->delete('/api/v1/brands/' . $brand['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseMissing('brands', ['title' => $data['title']]);
    }

    public function test_delete_brand_unauthorized()
    {
        $this->get('/api/v1/brands?limit=1');
        $response = $this->decodeResponseJson();

        $brand = $response['data'][0];
        $data = [
            'title' => 'Test Update Brand'
        ];

        $this->put('/api/v1/brands/' . $brand['uuid'], $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseHas('brands', ['title' => $brand['title']]);
        $this->assertDatabaseMissing('brands', ['title' => $data['title']]);
    }

    public function test_delete_brand_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $brand = Str::uuid();
        $data = [
            'title' => 'Test Update Brand'
        ];

        $this->delete('/api/v1/brands/' . $brand, $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }
}
