<?php

namespace Tests\AppBundle\Controller\Api;

use Tests\AppBundle\ApiTestCase;


class TokenControllerTest extends ApiTestCase
{
    public function testPOSTCreateToken()
    {
        //$this->createUser('weaverryan', 'I<3Pizza');
        $response = $this->client->post(self::PATH_TO_API.'/tokens', [
            'auth' => ['weaverryan', 'I<3Pizza']
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->asserter()->assertResponsePropertyExists(
            $response,
            'token'
        );
    }
}