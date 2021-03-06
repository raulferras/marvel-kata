<?php

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Marvel\Comic;
use Marvel\HttpComicsRepository;
use PHPUnit\Framework\TestCase;

class HttpComicsRepositoryTest extends TestCase
{
    private $mbnHost;

    function setUp(): void
    {
        $this->mbnHost = 'http://mountebank:2525';
        $this->configureNextWeekComics($this->mbnHost);
    }

    function test_I_get_something_as_response()
    {
        $marvel = $this->getHttpComicsRepository();
        $nextWeekReleases = $marvel->getNextWeekComics();

        self::assertTrue(!empty($nextWeekReleases));
    }

    function test_next_weeeks_first_commic_title_is_correct()
    {
        $marvel = $this->getHttpComicsRepository();
        $comics = $marvel->getNextWeekComics();
        $firstComic = $comics[0];
        self::assertEquals('All-New Invaders (2014) #11', $firstComic->getTitle());
        $this->assertRequestHasBeenMade('/v1/public/comics', [
            "dateDescriptor" => "nextWeek",
            "ts" => "1234567",
            "apikey" => "97f295907072a970c5df30d73d1f3816",
            "hash" => "292c2817662f28e5cccaed44841169b5"
        ]);
    }

    function test_next_weeeks_first_commic_thumbnailUrl_is_correct()
    {
        $marvel = $this->getHttpComicsRepository();
        $comics = $marvel->getNextWeekComics();
        /** @var Comic */
        $firstComic = $comics[0];
        self::assertEquals('http://i.annihil.us/u/prod/marvel/i/mg/7/20/543830ee97be9',
            $firstComic->getThumbnailUrl());
        $this->assertRequestHasBeenMade('/v1/public/comics', [
            "dateDescriptor" => "nextWeek",
            "ts" => "1234567",
            "apikey" => "97f295907072a970c5df30d73d1f3816",
            "hash" => "292c2817662f28e5cccaed44841169b5"
        ]);
    }

    function test_next_weeeks_first_commic_price_is_correct()
    {
        $marvel = $this->getHttpComicsRepository();
        $comics = $marvel->getNextWeekComics();
        /** @var Comic $firstComic */
        $firstComic = $comics[0];
        self::assertEquals(3.99, $firstComic->getPrice());
        $this->assertRequestHasBeenMade('/v1/public/comics', [
            "dateDescriptor" => "nextWeek",
            "ts" => "1234567",
            "apikey" => "97f295907072a970c5df30d73d1f3816",
            "hash" => "292c2817662f28e5cccaed44841169b5"
        ]);
    }

    private function getHttpComicsRepository(): HttpComicsRepository
    {
        return new HttpComicsRepository('1234567');
    }

    /**
     * @param string $mbnHost
     * @param        $json
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function configureNextWeekComics(string $mbnHost): void
    {
        $mountebankManagementClient = new Client();
        $mountebankManagementClient->request('DELETE', $mbnHost . '/imposters/3016');
        $config = [
            "port" => "3016",
            "protocol" => "http",
            "recordRequests" => "true",
            "name" => "origin",
            "defaultResponse" => ["statusCode" => 404,
                "body" => "Error"
            ],
            "stubs" => [
                [
                    "predicates" => [
                        [
                            "contains" => [
                                "method" => "GET",
                                "path" => "/v1/public/comics",
                                "query" => [
                                    "dateDescriptor" => "nextWeek",
                                    "ts" => "1234567",
                                    "apikey" => "97f295907072a970c5df30d73d1f3816",
                                    "hash" => "292c2817662f28e5cccaed44841169b5"
                                ]
                            ]
                        ]
                    ],
                    "responses" => [
                        [
                            "is" => [
                                "statusCode" => 200,
                                "body" => json_decode(file_get_contents(dirname(__DIR__).'/src/ApiResponse.json'))
                            ]
                        ]
                    ]
                ]
            ]
        ];


        $response = $mountebankManagementClient->request('POST',
            $mbnHost . '/imposters',
            [
                RequestOptions::BODY => json_encode($config),
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);
    }

    protected function assertRequestHasBeenMade($expectedPath, $expectedQueryParams = [])
    {
        $mountebankManagementClient = new Client();
        $response = $mountebankManagementClient->request('GET',
            $this->mbnHost . '/imposters/3016',
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
            ]);

        $output = $response->getBody()->getContents();
        $output = json_decode($output, true);

        $firstRequestMadeInThisImposter = 0;
        $request = $output['requests'][$firstRequestMadeInThisImposter];
        self::assertCount(1, $output['requests']);
        self::assertEquals($expectedPath, $request['path'], 'No requests made to expected path. Instead: '. $request['path']);
        self::assertEquals($expectedQueryParams, $request['query'], 'Unexpected body. Instead: '. $request['query']);

        return $output;
    }
}
