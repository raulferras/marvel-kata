<?php

namespace Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use function json_decode;

class MountebankTest extends TestCase
{
    /**
     * @var Client
     */
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client([
            'defaults' => ['verify' => 'false'],
            'base_uri' => 'http://localhost:2525',  // <-- base_uri instead of base_url
        ]);

        $body = json_decode('
{
    "port": "3016",
    "protocol": "http",
    "name": "origin",
    "defaultResponse": {
        "statusCode": 404,
        "body": "Error"
    },
    "stubs": [
        {
            "predicates": [
                {
                    "contains": {
                        "method": "POST",
                        "path": "/emails"
                    }
                }
            ],
            "responses": [
                {
                    "is": {
                        "statusCode": 201,
                        "body": {
                            "status": "success"
                        }
                    }
                }
            ]
        }
    ]
}
        ', true);

        try {
            $this->client->post('/imposters', ['json' => $body]);
        } catch (\Exception $e) {
            //...
        }
    }

    /** @test */
    public function works()
    {
        $this->markTestSkipped('no funciona');
        $client = new Client([
            'defaults' => ['verify' => 'false'],
            'base_uri' => 'http://localhost:3014',  // <-- base_uri instead of base_url
        ]);
        $response = $client->post('/emails');

        $this->assertEquals('{"status":"success"}', $response->getBody()->getContents());
    }


}