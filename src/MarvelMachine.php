<?php
namespace Marvel;

use GuzzleHttp\Client;

class MarvelMachine
{
    /**
     * @var array
     */
    private $response;
    /** @var  array */
    private $comics;

    public function __construct()
    {
        $this->response = array();
        $this->comics = array();
    }

    public function getNextWeekComics()
    {
        //$this->response = $this->makeRequest();
        $this->response = $this->makeRequestTest();

        $comics = $this->response->data->results;
        foreach($comics as $comicStdObject){
            $comic = new Comic($comicStdObject);
            array_push($this->comics, $comic);
        }
        return $this->comics;
    }

    private function makeRequest()
    {
        $publicKey = '97f295907072a970c5df30d73d1f3816';
        $privateKey = 'ed54a875c0dffad1fa6af84e66ff104434a1c6cc';
        $timestamp = time();
        $hash = md5($timestamp . $privateKey . $publicKey);

        $url = 'http://gateway.marvel.com:80/v1/public/comics?dateDescriptor=nextWeek&ts=' . $timestamp . '&apikey=' . $publicKey . '&hash=' . $hash;

        $client = new Client();
        $response = $client->get($url);
        return json_decode($response->getBody());
    }

    private function makeRequestTest()
    {
        return json_decode(file_get_contents(__DIR__.'/ApiResponse.json'));
    }

}