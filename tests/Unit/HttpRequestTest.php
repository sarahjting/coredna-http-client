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
            "option",
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
