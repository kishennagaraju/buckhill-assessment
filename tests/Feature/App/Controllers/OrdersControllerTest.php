<?php

namespace Tests\Feature\App\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BuckhillBaseTesting;

class OrdersControllerTest extends BuckhillBaseTesting
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_orders()
    {
        $this->get('/api/v1/order', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(1, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_orders_with_page()
    {
        $this->get('/api/v1/order?page=2', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();
        $this->assertResponseStatus(200);
        $this->assertEquals(2, $response['current_page']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_orders_with_all_query_params()
    {
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();

        $this->get('/api/v1/order?page=2&limit=2&sortBy=id&desc=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

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
    public function test_get_all_orders_with_env_pagination_limit()
    {
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();
        $order = $this->storeOrder();

        putenv('PAGINATION_LIMIT=5');
        $this->get('/api/v1/order', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

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
    public function test_get_single_order_success()
    {
        $this->get('/api/v1/order?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $response = $this->decodeResponseJson();

        $order = $response['data'][0];

        $this->get('/api/v1/order/' . $order['uuid'], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $this->assertResponseStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_single_order_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/order/42342342', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_order_success()
    {
        $order = $this->storeOrder();

        $this->assertNotEmpty($order);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_order_failure()
    {
        $order_status = $this->getOrderStatus()[0];
        $payment = $this->getPayments()[0];
        $products = $this->getProducts(3);

        $productArr = [];
        $price = 0;
        foreach ($products as $product) {
            $productArr[] = [
                'product' => $product['uuid'],
                'quantity' => 2
            ];
            $price += ($product['price'] * 2);
        }

        $data = [
            'order_status_uuid' => $order_status['uuid'],
            'payment_uuid' => $payment['uuid'],
            'products' => $productArr,
            'address' => $this->getAddress(),
            'delivery_fee' => '30.00',
            'amount' => $price
        ];

        $this->post('/api/v1/order', $data);

        $this->assertResponseStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_update_order_success()
    {
        $order = $this->storeOrder();
        $order_status = $this->getOrderStatus()[0];
        $payment = $this->getPayments()[0];
        $products = $this->getProducts(3);

        $productArr = [];
        $price = 0;
        foreach ($products as $product) {
            $productArr[] = [
                'product' => $product['uuid'],
                'quantity' => 3
            ];
            $price += ($product['price'] * 2);
        }

        $data = [
            'order_status_uuid' => $order_status['uuid'],
            'payment_uuid' => $payment['uuid'],
            'products' => $productArr,
            'address' => $this->getAddress(),
            'delivery_fee' => '300.00',
            'amount' => $price
        ];

        $this->put('/api/v1/order/' . $order['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

        $this->assertResponseStatus(200);
    }

    public function test_update_order_unauthorized()
    {
        $order = $this->storeOrder();
        $order_status = $this->getOrderStatus()[0];
        $payment = $this->getPayments()[0];
        $products = $this->getProducts(3);

        $productArr = [];
        $price = 0;
        foreach ($products as $product) {
            $productArr[] = [
                'product' => $product['uuid'],
                'quantity' => 3
            ];
            $price += ($product['price'] * 2);
        }

        $data = [
            'order_status_uuid' => $order_status['uuid'],
            'payment_uuid' => $payment['uuid'],
            'products' => $productArr,
            'address' => $this->getAddress(),
            'delivery_fee' => '300.00',
            'amount' => $price
        ];

        $this->put('/api/v1/order/' . $order['uuid'], $data);

        $this->assertResponseStatus(401);
    }

    public function test_update_order_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $order = $this->storeOrder();
        $order_status = $this->getOrderStatus()[0];
        $payment = $this->getPayments()[0];
        $products = $this->getProducts(3);

        $productArr = [];
        $price = 0;
        foreach ($products as $product) {
            $productArr[] = [
                'product' => $product['uuid'],
                'quantity' => 3
            ];
            $price += ($product['price'] * 2);
        }

        $data = [
            'order_status_uuid' => $order_status['uuid'],
            'payment_uuid' => $payment['uuid'],
            'products' => $productArr,
            'address' => $this->getAddress(),
            'delivery_fee' => '300.00',
            'amount' => $price
        ];

        $this->put('/api/v1/order/12312312231', $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_delete_order_success()
    {
        $order = $this->storeOrder();
        $this->delete('/api/v1/order/' . $order['uuid'] . '?token=' . $this->getAuthTokenForAdmin());

        $this->assertResponseStatus(200);
    }

    public function test_delete_order_unauthorized()
    {
        $order = $this->storeOrder();
        $this->delete('/api/v1/order/' . $order['uuid']);
        $this->assertResponseStatus(401);
    }

    public function test_delete_order_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->delete('/api/v1/order/234234234?token=' . $this->getAuthTokenForAdmin());
    }

    public function test_order_download_success()
    {
        $this->get('/api/v1/order?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        $response = $this->decodeResponseJson();
        $order = $response['data'][0];

        $this->get('/api/v1/order/' . $order['uuid'] . '/download', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        $this->response->assertHeader('Content-Disposition');
    }

    public function test_order_download_unauthorized()
    {
        $this->get('/api/v1/order?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        $response = $this->decodeResponseJson();
        $order = $response['data'][0];

        $this->get('/api/v1/order/' . $order['uuid'] . '/download');
        $this->assertResponseStatus(401);
    }

    public function test_order_download_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->get('/api/v1/order/32312312313123/download', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        $this->assertResponseStatus(401);
    }
}
