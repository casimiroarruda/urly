<?php
namespace Duodraco\UrlShortener\Context;

use Drakojn\Io\Mapper;
use Duodraco\UrlShortener\Data\Url;
use Symfony\Component\DependencyInjection\Container;

class Commandee
{
    /** @var  Container */
    protected $container;
    /** @var  Mapper */
    protected $urlMapper;

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
     * @return Url | bool (false)
     * @throws \Exception
     */
    public function getUrlById($hash)
    {
        $url = $this->container->get('mapper.url')->find(['hash' => $hash]);
        if(!$url){
            return false;
        }
        return current($url);
    }
}