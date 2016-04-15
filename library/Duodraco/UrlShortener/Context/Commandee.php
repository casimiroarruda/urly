<?php
namespace Duodraco\UrlShortener\Context;

use Drakojn\Io\Mapper;
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

    public function getUrlById($id)
    {

    }
}