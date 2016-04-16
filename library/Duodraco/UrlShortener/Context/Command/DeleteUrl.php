<?php
namespace Duodraco\UrlShortener\Context\Command;

use Duodraco\UrlShortener\Context\Command;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeleteUrl extends Command
{

    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function execute(Request $request, array $attributes = [])
    {
        $url = $this->commandee->getUrl($attributes['id']);
        if (!$url) {
            return new Response('', 404);
        }
        $code = $this->commandee->deleteUrl($url) ? 200 : 500;
        return new Response('', $code);
    }
}