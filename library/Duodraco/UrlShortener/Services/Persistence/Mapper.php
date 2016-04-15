<?php
namespace Duodraco\UrlShortener\Services\Persistence;

use Drakojn\Io\Mapper as DrakojnMapper;

/**
 * Purpose of Mapper
 */
class Mapper extends DrakojnMapper
{
    public function find(array $query = [])
    {
        $data = [];
        $map = $this->getMap();
        $identifier = $map->getIdentifier();
        $reflection = new \ReflectionProperty($map->getLocalName(), $identifier);
        $reflection->setAccessible(true);
        $original = parent::find($query);
        while (list($index, $object) = each($original)) {
            $data[$reflection->getValue($object)] = $object;
        }
        return $data;
    }
}