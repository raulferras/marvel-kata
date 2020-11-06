<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnit\Framework\TestCase;

class MountebankTest extends TestCase
{
    const HTTP_MOUNTEBANK_2525 = 'http://mountebank:2525';
    /** @var Client */
    private Client $mountebankManagementClient;
    private $host;

    protected function setUp(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        $this->host = $_ENV['API_HOST'];

        $this->mountebankManagementClient = new Client();

        $this->mountebankManagementClient->request('DELETE', self::HTTP_MOUNTEBANK_2525.'/imposters/3016');

        $json = '
{
    "port": "3016",
    "protocol": "http",
    "recordRequests": "true",
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
        ';

        $response = $this->mountebankManagementClient->request('POST',
            self::HTTP_MOUNTEBANK_2525 . '/imposters',
            [
                RequestOptions::BODY => $json,
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
    }

    /** @test */
    public function works()
    {
        $response = $this->mountebankManagementClient->request('POST', $this->host."/emails", [RequestOptions::JSON => ['var'=> 2]]);

        $output = $response->getBody()->getContents();
        $this->shouldHaveBeenCalled(3016);
    }

    protected function shouldHaveBeenCalled($port)
    {
        $response = $this->mountebankManagementClient->request('GET',
            self::HTTP_MOUNTEBANK_2525 . '/imposters/' . $port,
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
            ]);

        $output = $response->getBody()->getContents();
        $output = json_decode($output, true);

        var_dump($output['requests']);
        $request = $output['requests'][0];
        self::assertEquals('/emails', $request['path'], 'No requests made to expected path. Instead: '. $request['path']);
        self::assertEquals('{"var":1}', $request['body'], 'Unexpected body. Instead: '. $request['body']);

        return $output;
    }
}
