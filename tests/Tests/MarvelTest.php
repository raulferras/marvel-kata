<?php

namespace Tests;

use Marvel\Comic;
use Marvel\MarvelMachine;
use PHPUnit\Framework\TestCase;

class MarvelTest extends TestCase
{
    function test_I_get_something_as_response()
    {
        $marvel = new MarvelMachine();
        $nextWeekReleases = $marvel->getNextWeekComics();

        $this->assertTrue(!empty($nextWeekReleases));
    }

    function test_next_weeeks_first_commic_title_is_correct()
    {
        $marvel = new MarvelMachine();
        $comics = $marvel->getNextWeekComics();
        $firstComic = $comics[0];
        $this->assertEquals('All-New Invaders (2014) #11', $firstComic->getTitle());
    }

    function test_next_weeeks_first_commic_thumbnailUrl_is_correct()
    {
        $marvel = new MarvelMachine();
        $comics = $marvel->getNextWeekComics();
        /** @var Comic */
        $firstComic = $comics[0];
        $this->assertEquals('http://i.annihil.us/u/prod/marvel/i/mg/7/20/543830ee97be9', $firstComic->getThumbnailUrl());
    }

    function test_next_weeeks_first_commic_price_is_correct()
    {
        $marvel = new MarvelMachine();
        $comics = $marvel->getNextWeekComics();
        /** @var Comic $firstComic*/
        $firstComic = $comics[0];
        $this->assertEquals(3.99, $firstComic->getPrice());
    }


}
