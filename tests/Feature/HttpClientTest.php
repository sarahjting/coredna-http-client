<?php

namespace HttpClient\Tests;

use HttpClient\Exceptions\InvalidHttpMethodException;
use HttpClient\Exceptions\InvalidResponseException;
use HttpClient\HttpClient;
use HttpClient\HttpResponse;

class ClientTest extends TestCase
{
    public function test_client_throws_exception_for_invalid_method()
    {
        $client = new HttpClient();
        $this->expectException(InvalidHttpMethodException::class);
        $client->foo("https://www.google.com/");
    }

    public function test_client_throws_exception_for_invalid_response()
    {
        $client = new HttpClient();
        $this->expectException(InvalidResponseException::class);
        $client->get("https://www.page-does-not-exist.com");
    }

    public function test_client_can_send_GET_request()
    {
        $client = new HttpClient();
        $response = $client->get("https://www.google.com/");

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString("Google", $response->getBody());
    }

    public function test_client_can_send_POST_request()
    {
        $client = new HttpClient();
        $response = $client->post("https://graphql-pokemon.now.sh/", [
            "query" => "query{pokemons(first:10){name}}",
        ]);

        $this->assertInstanceOf(HttpResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertIsArray($response->getBody());
        $this->assertArrayHasKey("data", $response->getBody());
    }
}
