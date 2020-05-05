<?php

namespace HttpClient\Tests;

use HttpClient\Tests\TestCase;
use HttpClient\HttpResponse;
use HttpClient\Exceptions\InvalidResponseException;

class HttpResponseTest extends TestCase
{
    public function test_throws_exception_for_invalid_response()
    {
        $this->expectException(InvalidResponseException::class);
        $client = new HttpResponse([], "");
    }

    public function test_throws_exception_for_not_found_error()
    {
        $this->expectException(InvalidResponseException::class);
        $client = new HttpResponse([
            "HTTP/1.1 404 Not Found",
        ], "");
    }

    public function test_throws_exception_for_server_error()
    {
        $this->expectException(InvalidResponseException::class);
        $client = new HttpResponse([
            "HTTP/1.1 500 Internal Server Error",
        ], "");
    }
}
