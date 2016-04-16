<?php
namespace Duodraco\Urly\Context\Command;

use Duodraco\Urly\Context\Command;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlStats extends Command
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
        return new JsonResponse($url);
    }
}