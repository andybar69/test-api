<?php

namespace Tests\AppBundle\Controller\Api;

use Tests\AppBundle\ApiTestCase;
use GuzzleHttp;


class TokenControllerTest extends ApiTestCase
{
    public function testPOSTCreateToken()
    {
        $data = ['username' => 'API', 'password' => 'api'];
        $response = $this->client->post(self::PATH_TO_API.'/authenticate', [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            GuzzleHttp\RequestOptions::JSON => $data,
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists(
            $response,
            'token'
        );
    }

    public function testPOSTTokenInvalidCredentials()
    {
        $data = ['username' => 'API', 'password' => 'api4'];
        $response = $this->client->post(self::PATH_TO_API.'/authenticate', [
            'headers' => [
                'Content-Type' => 'application/json;charset=UTF-8',
            ],
            GuzzleHttp\RequestOptions::JSON => $data,
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }
}