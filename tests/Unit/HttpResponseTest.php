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

    public function test_returns_status_code_as_integer()
    {
        $response = new HttpResponse([
            "HTTP/1.1 201 Created",
        ], "");
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_returns_body_as_text()
    {
        $expected = "Foo";
        $response = new HttpResponse([
            "HTTP/1.1 200 OK",
        ], $expected);
        $this->assertEquals($expected, $response->getBody());
        $this->assertFalse($response->isJson());
    }

    public function test_returns_body_as_json()
    {
        $expected = ["foo" => "bar", "nested" => [1, 2, 3]];
        $response = new HttpResponse([
            "HTTP/1.1 200 Created",
            "Content-Type:application/json"
        ], json_encode($expected));
        $this->assertEquals($expected, $response->getBody());
        $this->assertEquals($expected, $response->getJson());
        $this->assertTrue($response->isJson());
    }
}
