<?php
namespace Duodraco\Urly\Context\Command;

use Duodraco\Urly\Context\Command;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CreateUrl extends Command
{
    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function execute(Request $request, array $attributes = [])
    {
        if (0 !== strpos($request->headers->get('Content-Type'), 'application/json')) {
            return new Response('Not json body', 500);
        }
        $data = json_decode($request->getContent());
        $url = $this->commandee->createUrl($attributes['userid'], $data->url);
        if (!$url) {
            return new Response('', 500);
        }
        return new JsonResponse($url, 201);
    }
}