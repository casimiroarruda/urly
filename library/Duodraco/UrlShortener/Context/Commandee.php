<?php
namespace Duodraco\UrlShortener\Context;

use Drakojn\Io\Mapper;
use Duodraco\UrlShortener\Data\Url;
use Duodraco\UrlShortener\Data\User;
use Duodraco\UrlShortener\Services\String\HashingBehaviour;
use PDO;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Container;

class Commandee
{
    /** @var  Container */
    protected $container;

    /**
     * Commandee constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param string $hash
     * @return bool | Url
     * @throws \Exception
     */
    public function getUrlById($hash)
    {
        $url = $this->container->get('mapper.url')->find(['hash' => $hash]);
        if (!$url) {
            return false;
        }
        return current($url);
    }

    /**
     * @return bool|int|User
     * @throws \Exception
     */
    public function createUser()
    {
        $hash = $this->container->get('hasher')->encode(rand(1, 9999));
        $mapper = $this->container->get('mapper.user');
        if ($mapper->find(['hash' => $hash])) {
            throw new InvalidArgumentException('Duplicate!');
            return false;
        }
        $user = new User();
        $user->setHash($hash);
        $user->setAlias('');
        $sucess = $this->container->get('mapper.user')->save($user);
        return $sucess ? $user : false;
    }

    /**
     * @param $userHash
     * @param $url
     * @return bool|Url|void
     * @throws \Exception
     */
    public function createUrl($userHash, $url)
    {
        $userMapper = $this->container->get('mapper.user');
        $urlMapper = $this->container->get('mapper.url');
        $user = $userMapper->find(['hash' => $userHash]);
        if (!$user) {
            throw new \InvalidArgumentException('User not found');
            return;
        }
        $urlObject = new Url();
        $urlObject->setUrl($url);
        $urlObject->setUserHash($userHash);
        $success = $urlMapper->save($urlObject);
        if (!$success) {
            return false;
        }
        $hash = $this->getHashFromId($this->container->get('hasher'), $urlObject->getId());
        $urlObject->setHash($hash);
        return $urlMapper->save($urlObject) ? $urlObject : false;
    }

    /**
     * @param Url $url
     * @throws \Exception
     */
    public function addHit(Url $url)
    {
        $url->addHit();
        $this->container->get('mapper.url')->save($url);
    }

    /**
     * @param bool|string $hash
     * @return array
     * @throws \Exception
     */
    public function getStats($hash = false)
    {
        /** @var \PDO $pdo */
        $pdo = $this->container->get('pdo');
        $filter = ['hash' => '', 'x' => 1];
        if ($hash) {
            $filter = ['hash' => $hash, 'x' => 0];
        }
        $row = $this->getGlobalStats($pdo, $filter);
        $topTen = $this->getTopTen($pdo, $filter);
        return $this->buildStats($row, $topTen);
    }

    /**
     * @param \stdClass $statsObject
     * @param array $topTen
     * @return array
     */
    protected function buildStats(\stdClass $statsObject, array $topTen)
    {
        return [
            "hits" => $statsObject->hits,
            "urlCount" => $statsObject->urlCount,
            "topUrls" => $topTen
        ];
    }

    protected function getTopTen(\PDO $pdo, array $filter = ['hash' => '', 'x' => 1])
    {
        $sql = <<<SQL
SELECT id, hash, url, user_hash userHash, hits
FROM url
WHERE user_hash = :hash OR :x
ORDER BY hits DESC 
LIMIT 10
SQL;
        $statement = $pdo->prepare($sql);
        $statement->execute($filter);
        $statement->setFetchMode(PDO::FETCH_CLASS, 'Duodraco\UrlShortener\Data\Url');
        return $statement->fetchAll();
    }

    protected function getGlobalStats(\PDO $pdo, array $filter = ['hash' => '', 'x' => 1])
    {
        $sql = <<<SQL
SELECT SUM(hits) hits, COUNT(id) urlCount FROM url
WHERE user_hash = :hash OR :x;
SQL;
        $statement = $pdo->prepare($sql);
        $statement->execute($filter);
        return $statement->fetchObject();
    }

    /**
     * @param $hash
     * @return bool|User
     * @throws \Exception
     */
    public function getUser($hash)
    {
        $user = $this->container->get('mapper.user')->find(['hash' => $hash]);
        return $user ? current($user) : false;
    }

    public function getUrl($hash)
    {
        $url = $this->container->get('mapper.url')->find(['hash' => $hash]);
        return $url ? current($url) : false;
    }

    public function deleteUrl(Url $url)
    {
        return $this->container->get('mapper.url')->delete($url);
    }

    use HashingBehaviour;
}