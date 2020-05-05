<?php

use HttpClient\Tests\TestCase;
use HttpClient\HttpRequest;

class HttpRequestTest extends TestCase
{
    private function httpMethods()
    {
        return [
            "get",
            "post",
            "put",
            "patch",
            "delete",
            "options",
            "head"
        ];
    }

    private function httpMethodsWithBody()
    {
        return [
            "post",
            "put",
            "patch",
        ];
    }

    public function test_can_return_uri()
    {
        $uri = "https://www.example.com";
        $request = new HttpRequest("get", $uri);
        $this->assertEquals($uri, $request->uri);
    }

    public function test_can_return_headers()
    {
        $uri = "https://www.example.com";
        $request = new HttpRequest("get", $uri);
        $this->assertEquals("Content-Type:application/json", $request->getHeaders());
    }

    public function test_can_pass_custom_headers()
    {
        $uri = "https://www.example.com";
        $request = new HttpRequest("get", $uri, [], [
            "Accept" => "application/xml"
        ]);
        $this->assertEquals("Accept:application/xml\r\nContent-Type:application/json", $request->getHeaders());
    }

    public function test_can_return_body()
    {
        $uri = "https://www.example.com";
        $body = [
            "foo" => "bar",
            'foo2' => 'bar2'
        ];
        $request = new HttpRequest("post", $uri, $body);
        $this->assertEquals(json_encode($body), $request->getBody());
    }

    public function test_can_return_simple_context()
    {
        $uri = "https://www.example.com";
        $request = new HttpRequest("get", $uri);
        $this->assertEquals([
            'http' => [
                'method' => 'GET',
                'header' => 'Content-Type:application/json',
                'content' => '',
            ]
        ], $request->getContext());
    }

    public function test_can_return_complex_context()
    {
        $uri = "https://www.example.com";
        $body = [
            "foo" => "bar",
            "foo2" => "bar2"
        ];
        $request = new HttpRequest("post", $uri, $body, [
            "Accept" => "application/xml",
        ]);
        $this->assertEquals([
            'http' => [
                'method' => 'POST',
                'header' => "Accept:application/xml\r\nContent-Type:application/json",
                'content' => json_encode($body),
            ]
        ], $request->getContext());
    }

    public function test_can_provide_array_of_available_methods()
    {
        $this->assertEquals($this->httpMethods(), HttpRequest::acceptableHttpMethods());
    }

    public function test_can_validate_available_method()
    {
        foreach ($this->httpMethods() as $method) {
            $this->assertTrue(HttpRequest::isAcceptableHttpMethod($method));
        }
        $this->assertFalse(HttpRequest::isAcceptableHttpMethod("foo"));
    }

    public function test_can_provide_array_of_methods_with_body()
    {
        $this->assertEquals($this->httpMethodsWithBody(), HttpRequest::httpMethodsWithBody());
    }

    public function test_can_validate_method_with_body()
    {
        foreach ($this->httpMethodsWithBody() as $method) {
            $this->assertTrue(HttpRequest::httpMethodAcceptsBody($method));
        }
        $this->assertFalse(HttpRequest::httpMethodAcceptsBody("get"));
        $this->assertFalse(HttpRequest::httpMethodAcceptsBody("foo"));
    }
}
