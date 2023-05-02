<?php

namespace Chomsky\Tests\Http;

use Chomsky\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function test_json_response_is_constructed_correctly()
    {
        // Construye una respuesta de tipo json usando el metodo correspondiente
        // y comprueba que el codigo de estado, las cabeceras y el contenido
        // son lo que te esperas.
        $content = ["test" => "foo", "num" => 2];
        $response = Response::json($content);

        $this->assertEquals(200, $response->status());
        $this->assertEquals(json_encode($content), $response->content());
        $this->assertEquals(["content-type" => "application/json"], $response->headers());
    }

    public function test_text_response_is_constructed_correctly()
    {
        // Construye una respuesta de tipo texto plano usando el metodo
        // correspondiente y comprueba que el codigo de estado, las cabeceras y el
        // contenido son lo que te esperas.
        $content = "test";
        $response = Response::text($content);

        $this->assertEquals(200, $response->status());
        $this->assertEquals($content, $response->content());
        $this->assertEquals(["content-type" => "text/plain"], $response->headers());
    }

    public function test_redirect_response_is_constructed_correctly()
    {
        // Construye una respuesta de tipo redirect usando el metodo
        // correspondiente y comprueba que el codigo de estado, las cabeceras y el
        // contenido son lo que te esperas.
        $uri = "/redirect/uri";
        $response = Response::redirect($uri);

        $this->assertEquals(302, $response->status());
        $this->assertNull($response->content());
        $this->assertEquals(["location" => $uri], $response->headers());
    }

    public function test_prepare_method_removes_content_headers_if_there_is_no_content()
    {
        // Comprueba que el metodo prepare de la respuesta elimina las cabeceras
        // relativas al contenido si es que no hay contenido.
        $response = new Response();
        $response->setContentType("Test");
        $response->setHeader("Content-Length", 10);
        $response->prepare();

        $this->assertEmpty($response->headers());
    }

    public function test_prepare_method_adds_content_length_header_if_there_is_content()
    {
        // Comprueba que el metodo prepare añade la cabecera Content-Length
        // con el valor correcto si la respuesta tiene contenido.
        $content = "test";
        $response = Response::text($content);
        $response->prepare();

        $this->assertEquals(strlen($content), $response->headers()["content-length"]);
    }
}
