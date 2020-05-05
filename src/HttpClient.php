<?php

namespace HttpClient;

use HttpClient\Exceptions\InvalidHttpMethodException;
use HttpClient\HttpRequest;
use HttpClient\HttpResponse;

/**
 * HTTP client. 
 * 
 * Example usage:
 *      $client = new HttpClient();
 *      $response = $client->get("https://www.example.com"); 
 *      $response = $client->post("https://www.example.com", [
 *              "foo" => "bar",
 *          ], [
 *              "Accept" => "application/xml",
 *          ]);
 */
class HttpClient
{
    /**
     * Magic function.
     * Takes GET, POST, PUT, PATCH, DELETE, OPTIONS or HEAD as method names
     * and calls the respective request.
     *
     * @param  string $method
     * @param  array $params
     * @return HttpResponse
     */
    public function __call(string $method, array $params): HttpResponse
    {
        if (!HttpRequest::isAcceptableHttpMethod($method)) {
            throw new InvalidHttpMethodException();
        }
        if (HttpRequest::httpMethodAcceptsBody($method)) {
            return $this->request(
                $method,
                $params[0],
                $params[1] ?? [],
                $params[2] ?? []
            );
        } else {
            return $this->request(
                $method,
                $params[0],
                [],
                $params[1] ?? []
            );
        }
    }

    /**
     * Processes a request.
     *
     * @param  string $method Method of the HTTP request.
     * @param  string $uri URI
     * @param  array $body Request body, as an associative array.
     * @param  array $headers Headers, as an associative array.
     * @return HttpResponse
     */
    public function request(
        string $method,
        string $uri,
        array $body = [],
        array $inHeaders = []
    ): HttpResponse {
        $request = new HttpRequest($method, $uri, $body, $inHeaders);
        [$headers, $result] = $this->executeRequest(
            $request->getUri(),
            $request->getContext()
        );
        return new HttpResponse($headers, $result);
    }

    /**
     * Executes a request. Wrapper for file_get_contents().
     *
     * @return array
     */
    public function executeRequest(string $uri, array $contextOptions): array
    {
        $result = @file_get_contents(
            $uri,
            false,
            stream_context_create($contextOptions)
        );
        return [$http_response_header ?? [], $result];
    }
}
