<?php

require __DIR__.'/vendor/autoload.php';

$client = new \GuzzleHttp\Client([
    'base_uri' => 'http://test.dev',
    //'http_errors' => false
]);


$data = [
    'firstName' => 'Garry',
    'lastName' => 'Lineker',
    'nickname' => 'goaleodor'
];
/*
try {
    $response = $client->post('/test-api/web/app_dev.php/api/authors', [
        'headers' => [
            'Content-Type' => 'application/json;charset=UTF-8',
        ],
        GuzzleHttp\RequestOptions::JSON => $data,
    ]);
    printOK($response);
}
catch (GuzzleHttp\Exception\RequestException $ex) {
    printError($ex);
}

/*
try {
    $response = $client->get('/test-api/web/app_dev.php/api/authors/5');
    printOK($response);
}
catch (GuzzleHttp\Exception\RequestException $ex) {
    printError($ex);
}
*/

try {
    $response = $client->get('/test-api/web/app_dev.php/api/authors', [
        'headers' => [
            'Accept' => 'application/json;charset=UTF-8',
        ]
    ]);
    printOK($response);
    $body = $response->getBody();
    $arr = json_decode($body, true);
    print_r($arr);
}
catch (GuzzleHttp\Exception\RequestException $ex) {
    printError($ex);
}

/*
try {
    $response = $client->put('/test-api/web/app_dev.php/api/authors/1', [
        'headers' => [
            'Accept' => 'application/json;charset=UTF-8',
        ],
        GuzzleHttp\RequestOptions::JSON => $data,
    ]);
    printOK($response);
    $body = $response->getBody();
    $arr = json_decode($body, true);
    print_r($arr);
}
catch (GuzzleHttp\Exception\RequestException $ex) {
    printError($ex);
}
*/
/*
try {
    $response = $client->patch('/test-api/web/app_dev.php/api/authors/1', [
        'headers' => [
            'Accept' => 'application/json;charset=UTF-8',
        ],
        GuzzleHttp\RequestOptions::JSON => $data,
    ]);
    printOK($response);
    $body = $response->getBody();
    $arr = json_decode($body, true);
    print_r($arr);
}
catch (GuzzleHttp\Exception\RequestException $ex) {
    printError($ex);
}
*/

function printOK($response) {
    print $response->getReasonPhrase().PHP_EOL;
    echo $response->getStatusCode().PHP_EOL;
    print_r($response->getHeaders()).PHP_EOL;
    echo $response->getBody().PHP_EOL;
}

function printError($exception) {
    if ($exception->hasResponse()) {
        $response = $exception->getResponse();
        echo (string) $response->getReasonPhrase().PHP_EOL;
        echo (string) $response->getStatusCode().PHP_EOL;
        print_r($response->getHeaders()).PHP_EOL;
        echo (string) $response->getBody().PHP_EOL;

    }
}