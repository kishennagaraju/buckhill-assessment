<?php

namespace Tests;

use App\Traits\Models\User;
use App\Traits\Services\Jwt;
use Faker\Factory;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class BuckhillBaseTesting extends TestCase
{
    use Jwt;
    use User;

    /**
     * @var mixed
     */
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
        Artisan::call('migrate --seed');
    }

    protected function tearDown(): void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /**
     * Call the given URI and return the Response.
     *
     * @param  string  $method
     * @param  string  $uri
     * @param  array  $parameters
     * @param  array  $cookies
     * @param  array  $files
     * @param  array  $server
     * @param  string|null  $content
     * @return \Illuminate\Testing\TestResponse
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $kernel = $this->app->make(HttpKernel::class);

        $files = array_merge($files, $this->extractFilesFromDataArray($parameters));

        $symfonyRequest = SymfonyRequest::create(
            $this->prepareUrlForRequest($uri), $method, $parameters,
            $cookies, $files, array_replace($this->serverVariables, $server), $content
        );

        $response = $kernel->handle(
            $request = Request::createFromBase($symfonyRequest)
        );

        $kernel->terminate($request, $response);

        if ($this->followRedirects) {
            $response = $this->followRedirects($response);
        }

        $this->response = $this->createTestResponse($response);
    }

    /**
     * Decoding the response content.
     *
     * @return mixed
     */
    public function decodeResponseJson()
    {
        return json_decode($this->response->content(), true);
    }

    /**
     * Created to assert the response status code for testing.
     *
     * @param int $code
     *
     * @return $this
     */
    public function assertResponseStatus($code)
    {
        $actual = $this->response->getStatusCode();

        $request = json_encode(app(Request::class)->all(), JSON_PRETTY_PRINT);

        $response = json_encode($this->response->getOriginalContent(), JSON_PRETTY_PRINT);
        PHPUnit::assertEquals(
            $code,
            $this->response->getStatusCode(),
            "Expected status code {$code}, got {$actual}.\n\nRequest:\n{$request}\n\nResponse:\n{$response}"
        );

        return $this;
    }

    /**
     * Created to assert the response status code for testing.
     *
     * @param int $code
     *
     * @return $this
     */
    public function assertResponseNotStatus($code)
    {
        $actual = $this->response->getStatusCode();

        $request = json_encode(app(Request::class)->all(), JSON_PRETTY_PRINT);

        $response = json_encode($this->response->getOriginalContent(), JSON_PRETTY_PRINT);
        PHPUnit::assertNotEquals(
            $code,
            $this->response->getStatusCode(),
            "Expected status code {$code}, got {$actual}.\n\nRequest:\n{$request}\n\nResponse:\n{$response}"
        );

        return $this;
    }

    /**
     * A helper method to dump out the content of the current response.
     *
     * @param bool $die
     */
    protected function dumpResponseContent($die = false)
    {
        echo(json_encode(json_decode($this->response->content()), JSON_PRETTY_PRINT));

        if ($die) {
            die;
        }
    }

    public function loginAdminUser()
    {
        $userDetails = $this->getUserModel()->newQuery()->where('is_admin', '=', 1)->first();
        $this->getUserModel()->newQuery()->where('id', '=', $userDetails->id)->update([
            'password' => Hash::make('admin')
        ]);

        $this->call('POST', 'api/v1/admin/login', [
            'email' => $userDetails->email,
            'password' => 'admin'
        ]);
    }

    public function loginUser()
    {
        $userDetails = $this->getUserModel()->newQuery()->where('is_admin', '=', 0)->first();
        $this->getUserModel()->newQuery()->where('id', '=', $userDetails->id)->update([
            'password' => Hash::make('userpassword')
        ]);

        $this->call('POST', 'api/v1/user/login', [
            'email' => $userDetails->email,
            'password' => 'userpassword'
        ]);
    }

    /**
     * Generate JWT Token for User Details for API testing.
     *
     * @param  array  $userDetails
     *
     * @return array
     */
    public function getJwtTokenForUser(array $userDetails, $isAdmin = false)
    {
        return $this->getJwtService()->generateJwtToken(array_merge($userDetails, ['is_admin' => $isAdmin]));
    }

    /**
     * @param  array  $headers
     *
     * @return void
     */
    public function setHeaders(array $headers)
    {
        $this->withHeaders($headers);
    }

    /**
     * @return mixed
     */
    public function getAdminUser()
    {
        return $this->getUserModel()->newQuery()->where('is_admin', '=', 1)->firstOrFail();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->getUserModel()->newQuery()->where('is_admin', '=', 0)->firstOrFail();
    }

    public function getAuthTokenForAdmin()
    {
        $this->loginAdminUser();
        $response = $this->decodeResponseJson();

        return $response['data']['token'];
    }

    public function getAuthTokenForUser()
    {
        $this->loginUser();
        $response = $this->decodeResponseJson();

        return $response['data']['token'];
    }

    public function getProducts($count = 1)
    {
        $this->get('/api/v1/products?limit=2');
        $response = $this->decodeResponseJson();
        return $response['data'];
    }

    public function getOrderStatus($count = 1)
    {
        $this->get('/api/v1/order-status?limit=1');
        $response = $this->decodeResponseJson();
        return $response['data'];
    }

    public function getPayments($count = 1)
    {
        $this->get('/api/v1/payments?limit=1', ['Authorization' => 'Bearer ' . $this->getAuthTokenForAdmin()]);
        $response = $this->decodeResponseJson();
        return $response['data'];
    }

    public function storeOrder($token = null)
    {
        $authToken = (!$token) ? $this->getAuthTokenForAdmin() : $token;
        $order_status = $this->getOrderStatus()[0];
        $payment = $this->getPayments()[0];
        $products = $this->getProducts(2);

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
            'delivery_fee' => '20.00',
            'amount' => $price
        ];

        $this->post('/api/v1/order', $data, ['Authorization' => 'Bearer ' . $authToken]);

        return $this->decodeResponseJson()['message'];
    }

    public function getAddress($includeShipping = true)
    {
        $faker = Factory::create();
        $address = [
            'billing' => $faker->address()
        ];

        if ($includeShipping) {
            $address['shipping'] = $faker->address();
        }

        return $address;
    }
}
