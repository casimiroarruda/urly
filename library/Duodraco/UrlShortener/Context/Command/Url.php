<?php
namespace Duodraco\UrlShortener\Context\Command;

use Duodraco\UrlShortener\Context\Command;
use Duodraco\UrlShortener\Context\Commandee;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Url extends Command
{

    /**
     * @param Container $container
     * @return Commandee
     */
    public function setupCommandee(Container $container)
    {
        $this->commandee = new Commandee($container);
    }

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function execute(Request $request, array $attributes = [])
    {
        $url = $this->commandee->getUrlById($attributes['id']);
        if (!$url) {
            return new Response('Not Found', 404);
        }
        return new RedirectResponse($url->getUrl(), 301);
    }
}