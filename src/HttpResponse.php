<?php

namespace HttpClient;

use HttpClient\Exceptions\InvalidResponseException;

class HttpResponse
{
    var $body;
    var $headers = [];
    var $statusCode;

    /**
     * __construct
     *
     * @param  mixed $headers
     * @param  mixed $payload
     * @return void
     */
    public function __construct(array $headers, string $payload)
    {
        $this->parseHeaders($headers);
        $this->parsePayload($payload);
    }

    /**
     * Loads headers.
     *
     * @param  array $headers
     * @return void
     */
    private function parseHeaders(array $headers): void
    {
        if (!count($headers)) {
            throw new InvalidResponseException();
        }

        $this->statusCode = explode(" ", $headers[0])[1];
        if ($this->statusCode >= 400 && $this->statusCode < 600) {
            throw new InvalidResponseException();
        }

        foreach (array_slice($headers, 1) as $header) {
            $header = explode(":", $header, 2);
            $this->headers[$header[0]] = trim($header[1]);
        }
    }

    /**
     * Loads payload. If content type is JSON, JSON will be parsed.
     *
     * @param  string $payload
     * @return void
     */
    private function parsePayload(string $payload): void
    {
        if ($this->isJson()) {
            $this->body = (array) json_decode($payload);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InvalidResponseException();
            }
        } else {
            $this->body = $payload;
        }
    }

    /**
     * Returns body of the response. 
     * In the case of JSON responses, this will return an associative array.
     * Otherwise it will return a string.
     *
     * @return string|array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Gets headers as associative array.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Returns status code as integer.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    /**
     * Returns whether the current response is json content.
     *
     * @return bool
     */
    public function isJson(): bool
    {
        return isset($this->headers["Content-Type"])
            && substr($this->headers["Content-Type"], 0, 16) === "application/json";
    }
}
