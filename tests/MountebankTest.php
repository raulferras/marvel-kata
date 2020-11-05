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

    protected function setUp(): void
    {
        $this->mountebankManagementClient = new Client();

        $this->mountebankManagementClient->request('DELETE', self::HTTP_MOUNTEBANK_2525.'/imposters/3016');

        $json = '
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
        $response = $this->mountebankManagementClient->request('POST', "http://mountebank:3016/emails");

        $output = $response->getBody()->getContents();
        echo "OUTPUT:" . $output . "\n";
        echo json_encode($this->shouldHaveBeenCalled(3016));
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
        var_dump($output);

        return $output;
    }
}
