<?php

namespace Chomsky\Server;

use Chomsky\Http\HttpMethod;
use Chomsky\Http\Request;
use Chomsky\Http\Response;
use Chomsky\Storage\File;

class PhpNativeServer implements Server
{

        /**
     * Get files from `$_FILES` global.
     *
     * @return array<string, \Chomsky\Storage\File>
     */
    protected function uploadedFiles(): array {
        $files = [];
        foreach ($_FILES as $key => $file) {
            if (!empty($file["tmp_name"])) {
                $files[$key] = new File(
                    file_get_contents($file["tmp_name"]),
                    $file["type"],
                    $file["name"],
                );
            }
        }

        return $files;
    }

    protected function requestData(): array {
        $headers = getallheaders();

        $isJson = isset($headers["Content-Type"])
            && $headers["Content-Type"] === "application/json";


        if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isJson) {
            return $_POST;
        }

        if ($isJson) {
            $data = json_decode(file_get_contents("php://input"), associative: true);
        } else {
            parse_str(file_get_contents("php://input"), $data);
        }

        return $data;
    }

    /**
     * Get the request sent by the client.
     *
     * @return Request
     */
    public function getRequest(): Request
    {
        return (new Request())
            ->setUri(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
            ->setMethod(HttpMethod::from($_SERVER['REQUEST_METHOD']))
            ->setHeaders(getallheaders())
            ->setData($this->requestData())
            ->setQuery($_GET);
    }


    /**
     * Send a response to the client
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response)
    {
        //PHP  envia Content-Type: text/html; charset=UTF-8 por defecto, pero si no hay contenido, no debería enviarlo
        //Content-Type no puede ser removido si no hay un valor previo
        header("Content-Type: None");
        header_remove("Content-Type");

        $response->prepare();
        http_response_code($response->status());
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }
        print($response->content());
    }
}
