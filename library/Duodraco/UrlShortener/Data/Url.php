<?php
namespace Duodraco\UrlShortener\Data;

class Url implements \JsonSerializable
{
    /** @var  int */
    protected $id;
    /** @var  string */
    protected $hash;
    /** @var  string */
    protected $url;
    /** @var  string */
    protected $userHash;
    /** @var  int */
    protected $hits = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getUserHash()
    {
        return $this->userHash;
    }

    /**
     * @param int $userHash
     */
    public function setUserHash($userHash)
    {
        $this->userHash = $userHash;
    }

    /**
     * @return int
     */
    public function getHits()
    {
        return $this->hits;
    }

    public function addHit()
    {
        $this->hits++;
    }

    public function jsonSerialize()
    {
        $baseShortUrl = "http://{$_SERVER['HTTP_HOST']}/";
        return (object)[
            "id" => $this->getId(),
            "hits" => $this->getHits(),
            "url" => $this->getUrl(),
            "shortUrl" => $baseShortUrl . $this->getHash()
        ];
    }

}