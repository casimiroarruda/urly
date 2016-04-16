<?php
namespace Duodraco\Urly\Context;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class Command
{
    /** @var  Commandee */
    protected $commandee;
    
    public final function __construct(Container $container)
    {
        $this->commandee = new Commandee($container);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    abstract public function execute(Request $request, array $attributes = []);
}