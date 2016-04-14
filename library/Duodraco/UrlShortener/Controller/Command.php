<?php
namespace Duodraco\UrlShortener\Controller;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Command
{
    /** @var  Commandee */
    protected $commandee;
    
    public function __construct(Container $container)
    {
        $this->commandee = $this->setupCommandee($container);
    }
    
    /**
     * @param Container $container
     * @return Commandee
     */
    abstract public function setupCommandee(Container $container);

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    abstract public function execute(Request $request, array $attributes = []);
}