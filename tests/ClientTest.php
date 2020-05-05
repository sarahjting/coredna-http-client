<?php

namespace HttpClient\Tests;

use HttpClient\HttpClient;

class ClientTest extends TestCase {
    public function test_client_constructs()
    {
        $client = new HttpClient();
    }
}