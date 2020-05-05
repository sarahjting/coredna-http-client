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
        new HttpResponse([], "");
    }

    public function test_throws_exception_for_not_found_error()
    {
        $this->expectException(InvalidResponseException::class);
        new HttpResponse([
            "HTTP/1.1 404 Not Found",
        ], "");
    }

    public function test_throws_exception_for_server_error()
    {
        $this->expectException(InvalidResponseException::class);
        new HttpResponse([
            "HTTP/1.1 500 Internal Server Error",
        ], "");
    }

    public function test_returns_headers_as_associative_array()
    {
        $response = new HttpResponse([
            "HTTP/1.1 200 OK",
            "Foo:Bar",
            "Hello:World",
        ], "");
        $this->assertEquals([
            "Foo" => "Bar",
            "Hello" => "World",
        ], $response->getHeaders());
    }
}
