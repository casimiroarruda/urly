<?php
namespace Duodraco\UrlShortener\Context;

use Drakojn\Io\Mapper;
use Duodraco\UrlShortener\Data\Url;
use Duodraco\UrlShortener\Data\User;
use Duodraco\UrlShortener\Services\String\HashingBehaviour;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Container;

class Commandee
{
    /** @var  Container */
    protected $container;

    /**
     * Commandee constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param string $hash
     * @return bool | Url
     * @throws \Exception
     */
    public function getUrlById($hash)
    {
        $url = $this->container->get('mapper.url')->find(['hash' => $hash]);
        if (!$url) {
            return false;
        }
        return current($url);
    }

    /**
     * @return bool|int|User
     * @throws \Exception
     */
    public function createUser()
    {
        $hash = $this->container->get('hasher')->encode(rand(1, 9999));
        $mapper = $this->container->get('mapper.user');
        if ($mapper->find(['hash' => $hash])) {
            throw new InvalidArgumentException('Duplicate!');
            return false;
        }
        $user = new User();
        $user->setHash($hash);
        $user->setAlias('');
        $sucess = $this->container->get('mapper.user')->save($user);
        return $sucess ? $user : false;
    }

    public function createUrl($userHash, $url)
    {
        $userMapper = $this->container->get('mapper.user');
        $urlMapper = $this->container->get('mapper.url');
        $user = $userMapper->find(['hash' => $userHash]);
        if (!$user) {
            throw new \InvalidArgumentException('User not found');
            return;
        }
        $urlObject = new Url();
        $urlObject->setUrl($url);
        $urlObject->setUserHash($userHash);
        $success = $urlMapper->save($urlObject);
        if (!$success) {
            return false;
        }
        $hash = $this->getHashFromId($this->container->get('hasher'), $urlObject->getId());
        $urlObject->setHash($hash);
        return $urlMapper->save($urlObject) ? $urlObject : false;
    }

    public function buildStatFromUrl(Url $url)
    {
        $baseShortUrl = "http://{$_SERVER['HTTP_HOST']}/";
        return (object)[
            "id" => $url->getId(),
            "hits" => $url->getHits(),
            "url" => $url->getUrl(),
            "shortUrl" => $baseShortUrl . $url->getHash()
        ];
    }

    use HashingBehaviour;
}