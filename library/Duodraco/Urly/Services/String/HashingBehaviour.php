<?php
namespace Duodraco\Urly\Services\String;

use Hashids\Hashids;

trait HashingBehaviour
{
    protected function getIdFromHash(Hashids $hasher, $hash)
    {
        return $hasher->decode($hash)[0] ?: null;
    }

    protected function getHashFromId(Hashids $hasher, $id)
    {
        return $hasher->encode($id) ?: null;
    }
}