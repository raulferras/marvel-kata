<?php

namespace Tests;

use Demyan112rv\MountebankPHP\Imposter;
use Demyan112rv\MountebankPHP\Mountebank;
use Demyan112rv\MountebankPHP\Predicate;
use Demyan112rv\MountebankPHP\Predicate\JsonPath;
use Demyan112rv\MountebankPHP\Predicate\XPath;
use Demyan112rv\MountebankPHP\Response;
use Demyan112rv\MountebankPHP\Response\Behavior;
use Demyan112rv\MountebankPHP\Stub;
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
        $script = '/app/tests/create.imposter.sh';
        shell_exec($script);
        /*
        $this->client = new Client([
            'defaults' => ['verify' => false],
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
            throw $e;
        }*/

        /*
        $response = new Response(Response::TYPE_IS);
        $response->setConfig([
            'statusCode' => 200,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => ['foo' => 'bar']
        ])->addBehavior(
            (new Behavior())
                ->setType(Behavior::TYPE_WAIT)
                ->setConfig((new Behavior\Config\Wait())->setValue(500))
        );

        $predicate = new Predicate(Predicate::OPERATOR_EQUALS);
        $predicate->setConfig(['path' => '/emails'])
                  ->setXPath((new XPath())->setSelector('selector')->setNs(['foo' => 'bar']))
                  ->setJsonPath((new JsonPath())->setSelector('selector'));


        $stub = new Stub();
        $stub->addResponse($response)->addPredicate($predicate);

        $imposter = new Imposter();
        $imposter->setName('Test imposter')
                 ->setPort(1234)
                 ->setProtocol(Imposter::PROTOCOL_HTTP)
                 ->addStub($stub);

// Mountbank config client
        $mb = new Mountebank(new \GuzzleHttp\Client());
        $mb->setHost('http://localhost')->setPort(2525);

// Add new imposter
        $response = $mb->addImposter($imposter);

// remove all imposters
        $response = $mb->removeImposters();*/
    }

    /** @test */
    public function works()
    {
        $client = new Client([
            'defaults' => ['verify' => false],
            'base_uri' => 'http://mountebank:80',  // <-- base_uri instead of base_url
        ]);
        $response = $client->post('/emails', ['verify' => false]);

        $this->assertEquals('{"status":"success"}', $response->getBody()->getContents());
    }
}
