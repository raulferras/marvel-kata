<?php
namespace Marvel;


class Comic
{
    /** @var  string */
    private $title;
    /** @var  string */
    private $thumbnailUrl;
    /** @var  string */
    private $price;

    public function __construct($comicStdObject)
    {
        $this->title = $comicStdObject->title;
        $this->thumbnailUrl = $comicStdObject->thumbnail->path;
        $this->price = isset($comicStdObject->prices[0])? $comicStdObject->prices[0]->price: 0;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * @param string $thumbnailUrl
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }


}
