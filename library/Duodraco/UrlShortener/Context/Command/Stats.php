<?php
namespace Duodraco\UrlShortener\Context\Command;

use Duodraco\UrlShortener\Context\Command;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Stats extends Command
{
    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function execute(Request $request, array $attributes = [])
    {
        $stats = $this->commandee->getStats();
        return new JsonResponse($stats);
    }
}