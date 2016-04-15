<?php
namespace Agrosmart\Services;

use Symfony\Component\DependencyInjection\Container;

abstract class WebContext
{
    protected $container;

    /**
     * Context constructor.
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
}
