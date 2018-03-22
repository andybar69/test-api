<?php

namespace Tests\AppBundle\Controller\Api;

//use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

//use PHPUnit\Framework\TestCase;
use Tests\AppBundle\ApiTestCase;

class AuthorControllerTest extends ApiTestCase
{
    public function testShow()
    {
        $response = $this->client->get('/test-api/web/app_dev.php/api/authors/1');
        $this->assertEquals(200, $response->getStatusCode());
        $finishedData = json_decode($response->getBody()->getContents(), true);
        dump($finishedData);
        $this->assertArrayHasKey('firstName4', $finishedData);

        /*print $response->getReasonPhrase().PHP_EOL;
        echo $response->getStatusCode().PHP_EOL;
        print_r($response->getHeaders()).PHP_EOL;
        echo $response->getBody().PHP_EOL;*/
    }

    public function testCREATEAuthor()
    {

    }

    public function testUPDATEAuthor()
    {

    }
}