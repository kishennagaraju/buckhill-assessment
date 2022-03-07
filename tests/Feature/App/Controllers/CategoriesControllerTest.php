<?php

namespace Tests\Feature\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class CategoriesControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_categories()
    {
        $this->get('/api/v1/categories');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(1, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_categories_with_page()
    {
        $this->get('/api/v1/categories?page=2');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_categories_with_all_query_params()
    {
        $this->get('/api/v1/categories?page=2&limit=2&sortBy=id&desc=1');

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
        $this->assertEquals(2, count($response['data']));
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_categories_with_env_pagination_limit()
    {
        putenv('PAGINATION_LIMIT=5');
        $this->get('/api/v1/categories');

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
    public function test_get_single_categories_success()
    {
        $this->get('/api/v1/categories?limit=1');

        $response = $this->decodeResponseJson();

        $blog = $response['data'][0];

        $this->get('/api/v1/categories/' . $blog['uuid']);

        $this->assertResponseStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_category_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/categories/42342342');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_category_success()
    {
        $data = [
            'title' => 'Test Category'
        ];

        $this->post('/api/v1/categories', $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('categories', ['title' => $data['title']]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_category_failure()
    {
        $data = [
            'title' => 'Test Category'
        ];

        $this->post('/api/v1/categories', $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_category_success()
    {
        $this->get('/api/v1/categories?limit=1');
        $response = $this->decodeResponseJson();

        $category = $response['data'][0];
        $data = [
            'title' => 'Test Update Category'
        ];

        $this->put('/api/v1/categories/' . $category['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('categories', ['title' => $data['title']]);
    }

    public function test_update_category_unauthorized()
    {
        $this->get('/api/v1/categories?limit=1');
        $response = $this->decodeResponseJson();

        $category = $response['data'][0];
        $data = [
            'title' => 'Test Update Category'
        ];

        $this->put('/api/v1/categories/' . $category['uuid'], $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseMissing('categories', ['title' => $data['title']]);
    }

    public function test_update_category_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $category = Str::uuid();
        $data = [
            'title' => 'Test Update Category'
        ];

        $this->put('/api/v1/categories/' . $category, $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_category_success()
    {
        $this->get('/api/v1/categories?limit=1');
        $response = $this->decodeResponseJson();

        $category = $response['data'][0];
        $data = [
            'title' => 'Test Update Category'
        ];

        $this->delete('/api/v1/categories/' . $category['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseMissing('categories', ['title' => $data['title']]);
    }

    public function test_delete_category_unauthorized()
    {
        $this->get('/api/v1/categories?limit=1');
        $response = $this->decodeResponseJson();

        $category = $response['data'][0];
        $data = [
            'title' => 'Test Update Category'
        ];

        $this->put('/api/v1/categories/' . $category['uuid'], $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseHas('categories', ['title' => $category['title']]);
        $this->assertDatabaseMissing('categories', ['title' => $data['title']]);
    }

    public function test_delete_category_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $category = Str::uuid();
        $data = [
            'title' => 'Test Update Category'
        ];

        $this->delete('/api/v1/categories/' . $category, $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }
}
