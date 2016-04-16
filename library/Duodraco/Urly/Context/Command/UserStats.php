<?php
namespace Duodraco\Urly\Context\Command;

use Duodraco\Urly\Context\Command;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserStats extends Command
{
    /**
     * @param Request $request
     * @param array $attributes
     * @return Response
     */
    public function execute(Request $request, array $attributes = [])
    {
        $user = $this->commandee->getUser($attributes['userid']);
        if (!$user) {
            return new Response('', 404);
        }
        $stats = $this->commandee->getStats($user->getHash());
        return new JsonResponse($stats);
    }
}