<?php

namespace Tests\Feature\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class PromotionsControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_promotions()
    {
        $this->get('/api/v1/main/promotions');

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
    public function test_get_all_promotions_with_page()
    {
        $this->get('/api/v1/main/promotions?page=2');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_promotions_with_all_query_params()
    {
        $this->get('/api/v1/main/promotions?page=2&limit=5&sortBy=id&desc=1');

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
    public function test_get_all_promotions_with_env_pagination_limit()
    {
        putenv('PAGINATION_LIMIT=5');
        $this->get('/api/v1/main/promotions');

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertEquals(1, $response['current_page']);
        $this->assertEquals(5, count($response['data']));
    }
}
