<?php

namespace Duodraco\UrlShortener\Services;

use Cekurte\Environment\Environment;
use Duodraco\UrlShortener\Services\Event\RequestEvent;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class Application implements HttpKernelInterface
{
    /** @var RouteCollection */
    protected $routes;
    protected $container;
    protected $dispatcher;
    protected static $instance;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $router = false;
        $cache = false;
        if(class_exists('Memcache')){
            $cache = new \Memcache;
            $router = $cache->get('router.routes');
        }
        if (!$router) {
            $locator = $this->container->get('file.locator');
            $routesLoader = new YamlFileLoader($locator);
            $router = new RouteCollection();
            $router->addCollection($routesLoader->load('routes.yaml'));
            if($cache){
                $cache->set('router.routes', $router);
            }
        }
        $this->routes = $router;
        $this->dispatcher = new EventDispatcher();
        self::$instance = $this;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }



    public function on($event, $callback)
    {
        $this->dispatcher->addListener($event, $callback);
    }

    public function fire($event)
    {
        return $this->dispatcher->dispatch($event);
    }

    protected function buildMatcher(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);
        $options = [];
        $closure = function () {
            return $this->routes;
        };
        return new Router(new ClosureLoader(), $closure, $options, $context);

    }

    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $event = new RequestEvent();
        $event->setRequest($request);
        $this->dispatcher->dispatch('request', $event);
        $matcher = $this->buildMatcher($request);
        /**
         * @var Logger $logger
         * @var Response $response
         */
        $logger = $this->container->get('app.logger');
        try {
            $attributes = $matcher->match($request->getPathInfo());
            $controllerReflection = new \ReflectionClass($attributes['command']);
            $controller = $controllerReflection->newInstanceArgs([$this->container]);
            $response = call_user_func_array([$controller, 'execute'], [$request, $attributes]);
            $allowHost = Environment::get('AGRO_ALLOW_HOST');
            $response->headers->set("Access-Control-Allow-Origin", $allowHost, true);
            $response->headers->set('Access-Control-Allow-Methods', "GET,POST,DELETE", true);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('', 404);
        } catch (AccessDeniedException $e) {
            $logger->addCritical('Access Denied! ' . $e->getMessage());
            $response = new RedirectResponse('/');
        } catch (UnauthorizedHttpException $e) {
            $logger->addCritical('Unauthorized! ' . $e->getMessage());
            $response = new RedirectResponse('/');
        } catch (MethodNotAllowedException $e) {
            $logger->addCritical('Method Not Allowed! ' . $e->getMessage());
            $response = new Response('', 405);
        } catch (\Exception $e) {
            $logger->addCritical('Problem! ' . $e->getMessage());
            $response = new RedirectResponse('/');
        }
        return $response;
    }

}
