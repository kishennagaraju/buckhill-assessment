<?php

namespace Tests\Feature\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\BuckhillBaseTesting;

class OrderStatusesControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_order_status()
    {
        $this->get('/api/v1/order-status');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(1, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_order_status_with_page()
    {
        $this->get('/api/v1/order-status?page=2');

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_order_status_with_all_query_params()
    {
        $this->get('/api/v1/order-status?page=2&limit=2&sortBy=id&desc=1');

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
    public function test_get_all_order_status_with_env_pagination_limit()
    {
        putenv('PAGINATION_LIMIT=5');
        $this->get('/api/v1/order-status');

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
    public function test_get_single_order_status_success()
    {
        $this->get('/api/v1/order-status?limit=1');

        $response = $this->decodeResponseJson();

        $order_status = $response['data'][0];

        $this->get('/api/v1/order-status/' . $order_status['uuid']);

        $this->assertResponseStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_order_status_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/order-status/42342342');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_order_status_success()
    {
        $data = [
            'title' => 'Test Order Status'
        ];

        $this->post('/api/v1/order-status', $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('order_statuses', ['title' => $data['title']]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_order_status_failure()
    {
        $data = [
            'title' => 'Test Order Status'
        ];

        $this->post('/api/v1/order-status', $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_order_status_success()
    {
        $this->get('/api/v1/order-status?limit=1');
        $response = $this->decodeResponseJson();

        $order_status = $response['data'][0];
        $data = [
            'title' => 'Test Update Order Status'
        ];

        $this->put('/api/v1/order-status/' . $order_status['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
        $this->assertDatabaseHas('order_statuses', ['title' => $data['title']]);
    }

    public function test_update_order_status_unauthorized()
    {
        $this->get('/api/v1/order-status?limit=1');
        $response = $this->decodeResponseJson();

        $order_status = $response['data'][0];
        $data = [
            'title' => 'Test Update Order Status'
        ];

        $this->put('/api/v1/order-status/' . $order_status['uuid'], $data);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseMissing('order_statuses', ['title' => $data['title']]);
    }

    public function test_update_order_status_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $order_status = Str::uuid();
        $data = [
            'title' => 'Test Update Order Status'
        ];

        $this->put('/api/v1/order-status/' . $order_status, $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_order_status_success()
    {
        $this->get('/api/v1/order-status?limit=1');
        $response = $this->decodeResponseJson();

        $order_status = $response['data'][0];

        $this->delete('/api/v1/order-status/' . $order_status['uuid'] . '?token=' . $this->getAuthTokenForAdmin());

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(200);
    }

    public function test_delete_order_status_unauthorized()
    {
        $this->get('/api/v1/order-status?limit=1');
        $response = $this->decodeResponseJson();

        $order_status = $response['data'][0];

        $this->delete('/api/v1/order-status/' . $order_status['uuid']);

        $response = $this->decodeResponseJson();

        $this->assertResponseStatus(401);
        $this->assertDatabaseHas('order_statuses', ['title' => $order_status['title']]);
    }

    public function test_delete_order_status_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->delete('/api/v1/order-status/2342342423?token=' . $this->getAuthTokenForAdmin());
    }
}
