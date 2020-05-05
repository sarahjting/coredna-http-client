<?php

namespace HttpClient\Tests;

use Mockery;

use HttpClient\Exceptions\InvalidHttpMethodException;
use HttpClient\Exceptions\InvalidResponseException;
use HttpClient\HttpClient;
use HttpClient\HttpResponse;

class ClientTest extends TestCase
{
    var $client;

    public function setUp(): void
    {
        parent::setUp();
        $this->client = Mockery::mock('HttpClient\HttpClient')->makePartial();
    }

    public function test_client_throws_exception_for_invalid_method()
    {
        $this->expectException(InvalidHttpMethodException::class);
        $this->client->foo("https://www.example.com/");
    }

    public function test_client_throws_exception_for_invalid_response()
    {
        $this->expectException(InvalidResponseException::class);
        $this->client->shouldReceive('executeRequest')->andReturn([
            ["HTTP/1.1 404 Not Found"],
            ""
        ]);
        $this->client->get("https://www.example.com/missing-page");
    }

    public function test_client_can_send_bodyless_requests()
    {
        $uri = "https://www.example.com/";
        $expected = "Hello World";

        foreach (["get", "delete", "option", "head"] as $method) {
            $this->client
                ->shouldReceive('executeRequest')
                ->with($uri, [
                    'http' => [
                        "method" => strtoupper($method),
                        "header" => "Content-Type:application/json",
                        "content" => "",
                    ]
                ])->andReturn([
                    ["HTTP/1.1 200 OK"],
                    $expected
                ]);

            $response = $this->client->$method($uri);

            $this->assertInstanceOf(HttpResponse::class, $response);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($expected, $response->getBody());
        }
    }

    public function test_client_can_send_bodied_request()
    {
        $uri = "https://www.example.com/";
        $input = ["foo" => "bar"];
        $expected = ["Hello" => "World"];

        foreach (["post", "put", "patch"] as $method) {
            $this->client->shouldReceive('executeRequest')
                ->with($uri, [
                    'http' => [
                        "method" => strtoupper($method),
                        "header" => "Content-Type:application/json",
                        "content" => http_build_query($input),
                    ]
                ])
                ->andReturn([
                    [
                        "HTTP/1.1 200 OK",
                        'Content-Type:application/json'
                    ],
                    json_encode($expected)
                ]);

            $response = $this->client->$method($uri, $input);

            $this->assertInstanceOf(HttpResponse::class, $response);
            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($expected, $response->getBody());
        }
    }
}
