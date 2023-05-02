<?php

namespace Chomsky;

use Chomsky\Config\Config;
use Chomsky\Container\Container;
use Chomsky\Database\Drivers\DatabaseDriver;
use Chomsky\Database\Drivers\PdoDriver;
use Chomsky\Database\Model;
use Chomsky\Http\HttpMethod;
use Chomsky\Http\HttpNotFoundException;
use Chomsky\Http\Request;
use Chomsky\Http\Response;
use Chomsky\Routing\Router;
use Chomsky\Server\PhpNativeServer;
use Chomsky\Server\Server;
use Chomsky\Session\PhpNativeSessionStorage;
use Chomsky\Session\Session;
use Chomsky\Session\SessionStorage;
use Chomsky\Validation\Exceptions\ValidationException;
use Chomsky\Validation\Rule;
use Chomsky\View\RinzlerEngine;
use Chomsky\View\StencilEngine;
use Chomsky\View\ViewEngine;
use Dotenv\Dotenv;
use ReflectionClass;
use Throwable;

/**
 *
 */
class App
{

    public static string $root;
    /**
     * Singleton arquitecture
     */
    public Router $router;

    /**
     * @var Request
     */
    public Request $request;

    /**
     * @var Server
     */
    public Server $server;

    /**
     * @var ViewEngine
     */
    public ViewEngine $viewEngine;

    /**
     * @var Session
     */
    public Session $session;

    public DatabaseDriver $database;

    /**
     * @return Container\string|mixed
     */
    public static function bootstrap(string $root)
    {
        self::$root=$root;

        $app = singleton(self::class);
        return $app
            ->loadConfig()
            ->runServiceProviders("boot")
            ->setHttpHandlers()
            ->setupDatabaseConnection()
            ->runServiceProviders("runtime");
        // echo "<pre>";
        // ($app);
        // ($app->view_engine);
        // echo "</pre>";
        // exit;
        return $app;
    }

    /**
     * @return void
     */
    public function prepareNextRequest()
    {
        if ($this->request->method() == HttpMethod::GET) {
            $this->session->set('_previous', $this->request->uri());
        }
    }

    /**
     * @param Response $response
     * @return void
     */
    protected function terminate(Response $response)
    {
        $this->prepareNextRequest();
        $this->server->sendResponse($response);
        $this->database->close();
        exit();
    }

    protected function runServiceProviders(string $type):self{
        foreach (config('providers.'.$type) as $provider) {
            $provider = new $provider();
            $provider->registerServices();
        }
        return $this;
    }

    protected function setHttpHandlers():self{
        $this->router = singleton(Router::class);
        $this->server = app(Server::class);
        $this->request = singleton(Request::class,  fn()=>$this->server->getRequest());
        $this->session = singleton(Session::class, fn () => new Session(app(SessionStorage::class)));

        return $this;
    }

    protected function setupDatabaseConnection():self{
        $this->database = app(DatabaseDriver::class);
        $this->database->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password")
        );
        Model::setDatabaseDriver($this->database);
        return $this;
    }

    protected function loadConfig()
    {
        Dotenv::createImmutable(self::$root)->load();
        Config::load(self::$root."/config");

        return $this;
    }

    /**
     * @return void
     */
    public function run()
    {
        try {
            $this->terminate($this->router->resolve($this->request));
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text("Not Found")->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(back()->withErrors($e->errors(), 422));
        } catch (Throwable $e) {
            $error = new ReflectionClass($e);
            $response = json([
                "error" => $error->getShortName(),
                "message" => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                "trace" => $e->getTraceAsString()
            ]);

            $this->abort($response->setStatus(500));
        }
    }


    /**
     * @param Response $response
     * @return void
     */
    public function abort(Response $response)
    {
        $this->terminate($response);
    }
}
