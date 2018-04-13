<?php

namespace Tests\AppBundle\Controller\Api;

use GuzzleHttp;
use Tests\AppBundle\ApiTestCase;


class AuthorControllerTest extends ApiTestCase
{
    const PATH_TO_API = '/test-api/web/app_dev.php/api';
    public function testShow()
    {
        $response = $this->client->get(self::PATH_TO_API.'/authors/1', [
            'headers' => [
                'Accept' => 'application/json;charset=UTF-8',
            ],
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $finishedData = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('firstName', $finishedData);

    }

    public function testPOST()
    {
        $data = [
            'firstName' => 'user_'.substr(uniqid(), 0, 10),
            'lastName' => 'first_'.substr(uniqid(), 0, 10),
        ];
        $response = $this->client->post('/test-api/web/app_dev.php/api/authors', [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            GuzzleHttp\RequestOptions::JSON => $data,
        ]);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $finishedData = json_decode($response->getBody()->getContents(), true);
        $this->assertArrayHasKey('lastName', $finishedData);
    }

    public function testGETAuthor()
    {

    }

    public function testUPDATEAuthor()
    {

    }

    public function testValidationErrors()
    {
        $data = [
            'firstName' => 'user_'.substr(uniqid(), 0, 10),
            'lastName' => 'first_'.substr(uniqid(), 0, 10),
        ];
        $response = $this->client->post('/test-api/web/app_dev.php/api/authors', [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            GuzzleHttp\RequestOptions::JSON => $data,
        ]);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeader('Content-Type'));
    }

    public function testInvalidJson()
    {
        $invalidBody = <<<EOF
{
    "nickname": "JohnnyRobot",
    "firstName" : "2",
    "lastName": "I'm from a test!"
}
EOF;
        $response = $this->client->post('/test-api/web/app_dev.php/api/authors', [
            'body' => $invalidBody
        ]);
        $this->assertEquals(400, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyEquals($response, 'type', 'invalid_body_format');
    }

    public function test404Exception()
    {
        $response = $this->client->get('/test-api/web/app_dev.php/api/authors/fake');
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeader('Content-Type'));
        $this->asserter()->assertResponsePropertyEquals($response, 'type', 'about:blank');
        $this->asserter()->assertResponsePropertyEquals($response, 'title', 'Not Found');
    }
}