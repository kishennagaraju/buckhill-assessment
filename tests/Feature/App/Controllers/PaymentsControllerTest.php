<?php

    namespace Tests\Feature\App\Controllers;

    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Support\Str;
    use Tests\BuckhillBaseTesting;

    class PaymentsControllerTest extends BuckhillBaseTesting
    {
        use RefreshDatabase;

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_get_all_payments()
        {
            $this->get('/api/v1/payments', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

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
        public function test_get_all_payments_with_page()
        {
            $this->get('/api/v1/payments?page=2', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

            $response = $this->decodeResponseJson();
            $this->assertResponseStatus(200);
            $this->assertEquals(2, $response['current_page']);
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_get_all_payments_with_all_query_params()
        {
            $this->get('/api/v1/payments?page=2&limit=5&sortBy=id&desc=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

            $response = $this->decodeResponseJson();

            $this->assertResponseStatus(200);
            $this->assertEquals(2, $response['current_page']);
            $this->assertEquals(4, count($response['data']));
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_get_all_payments_with_env_pagination_limit()
        {
            putenv('PAGINATION_LIMIT=5');
            $this->get('/api/v1/payments', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

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
        public function test_get_single_payment_success()
        {
            $this->get('/api/v1/payments?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

            $response = $this->decodeResponseJson();

            $payment = $response['data'][0];

            $this->get('/api/v1/payments/' . $payment['uuid'], ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

            $this->assertResponseStatus(200);
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_get_single_payment_not_found()
        {
            $this->expectException(ModelNotFoundException::class);
            $this->get('/api/v1/payments/42342342', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_store_payment_success()
        {
            $data = [
                'type' => 'credit_card',
                'details' => [
                    "cvv" => 123,
                    "number" => "4535535345345345",
                    "expire_date" => "04/24",
                    "holder_name" => "Test User"
                ]
            ];

            $this->post('/api/v1/payments', $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

            $response = $this->decodeResponseJson();

            $this->assertResponseStatus(200);
            $this->assertDatabaseHas('payments', ['details' => json_encode($data['details'])]);
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_store_payment_failure()
        {
            $data = [
                'type' => 'credit_card',
                'details' => [
                    "cvv" => 123,
                    "number" => "4535535345345345",
                    "expire_date" => "04/24",
                    "holder_name" => "Test User"
                ]
            ];

            $this->post('/api/v1/payments', $data);

            $response = $this->decodeResponseJson();

            $this->assertResponseStatus(401);
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_update_payment_success()
        {
            $this->get('/api/v1/payments?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
            $response = $this->decodeResponseJson();

            $payment = $response['data'][0];

            $data = [
                'type' => 'cash_on_delivery',
                'details' => [
                    "address" => "Test Street",
                    "last_name" => "Update",
                    "first_name" => "Test",
                ]
            ];

            $this->put('/api/v1/payments/' . $payment['uuid'], $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);

            $response = $this->decodeResponseJson();

            $this->assertResponseStatus(200);
            $this->assertDatabaseHas('payments', [
                'uuid' => $response['message']['uuid'],
                'details' => json_encode($data['details'])
            ]);
        }

        public function test_update_payment_unauthorized()
        {
            $this->get('/api/v1/payments?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
            $response = $this->decodeResponseJson();

            $payment = $response['data'][0];
            $data = [
                'type' => 'cash_on_delivery',
                'details' => [
                    "address" => "Test Street",
                    "last_name" => "Update",
                    "first_name" => "Test",
                ]
            ];

            $this->put('/api/v1/payments/' . $payment['uuid'], $data);

            $this->assertResponseStatus(401);
        }

        public function test_update_payment_not_found()
        {
            $this->expectException(ModelNotFoundException::class);
            $payment = Str::uuid();
            $data = [
                'type' => 'cash_on_delivery',
                'details' => [
                    "address" => "Test Street",
                    "last_name" => "Update",
                    "first_name" => "Test",
                ]
            ];

            $this->put('/api/v1/payments/' . $payment, $data, ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        }

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_delete_payment_success()
        {
            $this->get('/api/v1/payments?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
            $response = $this->decodeResponseJson();

            $payment = $response['data'][0];
            $this->delete('/api/v1/payments/' . $payment['uuid'] . '?token=' . $this->getAuthTokenForAdmin());

            $response = $this->decodeResponseJson();

            $this->assertResponseStatus(200);
            $this->assertDatabaseMissing('payments', ['uuid' => $payment['uuid']]);
        }

        public function test_delete_payment_unauthorized()
        {
            $this->get('/api/v1/payments?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
            $response = $this->decodeResponseJson();

            $payment = $response['data'][0];

            $this->delete('/api/v1/payments/' . $payment['uuid']);

            $this->assertResponseStatus(401);
        }

        public function test_delete_payment_not_found()
        {
            $this->expectException(ModelNotFoundException::class);
            $payment = Str::uuid();
            $this->delete('/api/v1/payments/' . $payment . '?token=' . $this->getAuthTokenForAdmin());
        }
    }
