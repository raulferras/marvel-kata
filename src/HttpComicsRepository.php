<?php
namespace Marvel;

use GuzzleHttp\Client;

class HttpComicsRepository
{
//    const GATEWAY_MARVEL_COM_80 = 'mountebank:3016';
    private $GATEWAY_MARVEL_COM_80 = 'gateway.marvel.com:80';

    /**
     * @var array
     */
    private $response;
    /** @var  array */
    private $comics;
    private string $timestamp;

    public function __construct(string $timestamp)
    {
        $this->response = array();
        $this->comics = array();
        $this->timestamp = $timestamp;

        $this->loadEnvVariables();
    }



    public function getNextWeekComics()
    {
        $this->response = $this->makeRequest();
//        $this->response = $this->makeRequestTest();
        var_dump($this->response);

        $comics = $this->response->data->results;
        foreach($comics as $comicStdObject){
            $comic = new Comic($comicStdObject);
            $this->comics[] = $comic;
        }
        return $this->comics;
    }

    private function makeRequest()
    {
        $publicKey = '97f295907072a970c5df30d73d1f3816';
        $privateKey = 'ed54a875c0dffad1fa6af84e66ff104434a1c6cc';
        $hash = md5($this->timestamp . $privateKey . $publicKey);

        $url = 'http://' . $this->GATEWAY_MARVEL_COM_80 . '/v1/public/comics?dateDescriptor=nextWeek&ts='
            . $this->timestamp . '&apikey=' . $publicKey . '&hash=' . $hash;

        $client = new Client();
        $response = $client->get($url);
        return json_decode($response->getBody());
    }

    private function makeRequestTest()
    {
        return json_decode(file_get_contents(__DIR__.'/ApiResponse.json'));
    }

    private function loadEnvVariables(): void
    {
        $dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        $this->GATEWAY_MARVEL_COM_80 = $_ENV['API_HOST'];
    }

}