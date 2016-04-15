<?php
chdir(__DIR__);
require '../vendor/autoload.php';
use Cekurte\Environment\Environment;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;

$container = false;
$freshContainer = false;
$cache = false;
$tempDir = rtrim(Environment::get('TMP'), DIRECTORY_SEPARATOR);
if(class_exists('Memcache')){
    $cache = new Memcache;
    $container = $cache->get('container');
}
if (!$container) {
    $container = new ContainerBuilder();
    $container->setParameter('app.path', __DIR__);
    $container->setParameter('pdo.dsn', Environment::get('DB_DSN'));
    $container->setParameter('pdo.user', Environment::get('DB_USER'));
    $container->setParameter('pdo.pw', Environment::get('DB_PASS'));
    $container->setParameter('pdo.options', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_AUTOCOMMIT => true,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true
    ]);
    $container->setParameter('tmp', $tempDir);
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config/'));
    $loader->load('config.yaml');
    $freshContainer = true;
    $container->compile();
    if($cache){
        $cache->add('container', $container);
    }
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);

$application = $container->get('application.web');
$request = Request::createFromGlobals();
$response = $application->handle($request);
$response->send();
