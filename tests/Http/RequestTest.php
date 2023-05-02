<?php

namespace Chomsky\Tests\Http;

use Chomsky\Http\HttpMethod;
use Chomsky\Http\Request;
use Chomsky\Routing\Route;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function test_request_returns_data_obtained_from_server_correctly()
    {
        // Crea un objeto de tipo Chomsk\Http\Request. Utiliza los setters para
        // poner el valor de la URI, el metodo HTTP, los query parameters y
        // el POST data. Despues comprueba que que los getters devuelven
        // lo que has puesto.
        $uri = '/test/';
        $queryParams = ['a' => 1, 'b' => 2, 'test' => 'foo'];
        $postData = ['post' => 'test', 'foo' => 'bar'];

        $request = (new Request())
            ->setUri($uri)
            ->setMethod(HttpMethod::POST)
            ->setQuery($queryParams)
            ->setData($postData);

        $this->assertEquals($uri, $request->uri());
        $this->assertEquals($queryParams, $request->query());
        $this->assertEquals($postData, $request->data());
        $this->assertEquals(HttpMethod::POST, $request->method());
    }

    public function test_data_returns_value_if_key_is_given()
    {
        $data = ['test' => 5, 'foo' => 1, 'bar' => 2];
        $request = (new Request())->setData($data);

        $this->assertEquals($request->data('test'), 5);
        $this->assertEquals($request->data('foo'), 1);
        $this->assertNull($request->data("doesn't exist"));
    }

    public function test_query_returns_value_if_key_is_given()
    {
        $data = ['test' => 5, 'foo' => 1, 'bar' => 2];
        $request = (new Request())->setQuery($data);

        $this->assertEquals($request->query('test'), 5);
        $this->assertEquals($request->query('foo'), 1);
        $this->assertNull($request->query("doesn't exist"));
    }

    public function test_route_parameters_returns_value_if_key_is_given()
    {
        $route = new Route('/test/{param}/foo/{bar}', fn () => "test");
        $request = (new Request())
            ->setRoute($route)
            ->setUri('/test/1/foo/2');

        $this->assertEquals($request->routeParameters('param'), 1);
        $this->assertEquals($request->routeParameters('bar'), 2);
        $this->assertNull($request->routeParameters("doesn't exist"));
    }
}
