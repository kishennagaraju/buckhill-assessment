<?php

namespace Tests;

use App\Traits\Services\Jwt;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Assert as PHPUnit;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class BuckhillBaseTesting extends TestCase
{
    use Jwt;

    /**
     * @var mixed
     */
    protected $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        config(['app.url' => env('TESTING_BASE_URL', 'http://localhost')]);
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

    /**
     * Generate JWT Token for User Details for API testing.
     *
     * @param  array  $userDetails
     *
     * @return array
     */
    public function getJwtTokenForUser(array $userDetails)
    {
        return $this->getJwtService()->generateJwtToken($userDetails);
    }

    /**
     *
     */
    public function setHeaders($headers)
    {
        $this->withHeaders($headers);
    }
}
