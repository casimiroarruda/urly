<?php
namespace Duodraco\UrlShortener\Data;

class Url
{
    /** @var  int */
    protected $id;
    /** @var  string */
    protected $hash;
    /** @var  string */
    protected $url;
    /** @var  int */
    protected $userId;
    /** @var  int */
    protected $hits;

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
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
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
}