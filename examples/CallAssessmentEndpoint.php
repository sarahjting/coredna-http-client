<?php

require("./vendor/autoload.php");

use HttpClient\HttpClient;

$httpClient = new HttpClient();

$endpoint = "https://www.coredna.com/assessment-endpoint.php";

$authTokenRes = $httpClient->options($endpoint);
$authToken = $authTokenRes->getBody();
$authTokenStatus = $authTokenRes->getStatusCode();
echo "[{$authTokenStatus}] Auth token: {$authToken}" . PHP_EOL;

$postRes = $httpClient->post($endpoint, [
    "name" => "Sarah Ting",
    "email" => "sarah.j.ting@gmail.com",
    "url" => "https://www.github.com/sarahjting/coredna-http-client",
], [
    "Authorization" => "Bearer {$authToken}",
]);

$postResStatus = $postRes->getStatusCode();
echo "[{$postResStatus}] Done!" . PHP_EOL;
