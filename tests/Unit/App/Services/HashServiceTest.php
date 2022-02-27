<?php

namespace Tests\Unit\App\Services;

use App\Traits\Services\Hash;
use Tests\TestCase;

class HashServiceTest extends TestCase
{
    use Hash;

    public function test_create_and_verify_hash_success()
    {
        $string = "Hello";

        $hash = $this->getHashService()->generateHash($string);

        $this->assertTrue($this->getHashService()->verifyHashForString($string, $hash));
    }

    public function test_create_and_verify_hash_failure()
    {
        $string = "Hello";

        $hash = $this->getHashService()->generateHash($string);

        $this->assertFalse($this->getHashService()->verifyHashForString("Hello2", $hash));
    }
}
