<?php

namespace Chomsky\Http;

use Chomsky\App;
use Chomsky\Container\Container;
use Chomsky\View\ViewEngine;

/**
 * HTTP response that will be sent to the client.
 */
class Response
{
    /**
     * response HTTP status code
     *
     * @var integer
     */
    protected int $status=200;

    /**
     * response HTTP headers
     *
     * @var array<string,string>
     */
    protected array $headers=[];

    /**
     * response content
     *
     * @var string
     */
    protected ?string $content=null;

    //Getter and Setter for status

    /**
     * Get response HTTP status code
     *
     * @return integer
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Set response HTTP status code
     *
     * @param integer $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get response HTTP headers
     *
     * @return self
     */
    public function headers(?string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->headers;
        }

        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * Set response HTTP header
     *
     * @param string $header
     * @param string $value
     * @return self
     */
    public function setHeader(string $header, string $value): self
    {
        $this->headers[strtolower($header)] = $value;
        return $this;
    }

    /**
     * Remove response HTTP header
     * @param string $header
     * @return void
     */
    public function removeHeader(string $header)
    {
        unset($this->headers[strtolower($header)]);
    }

    /**
     * Set HTTP header Content-Type
     *
     * @return self
     */
    public function setContentType(string $value): self
    {
        $this->setHeader('Content-Type', $value);
        return $this;
    }

    /**
     * Get response content
     *
     * @return string
     */
    public function content(): ?string
    {
        return $this->content;
    }

    /**
     * Set response content
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Prepare response to be sent, setting default Content-Type header
     * @return void
     */
    public function prepare()
    {
        if (is_null($this->content)) {
            $this->removeHeader('Content-Type');
            $this->removeHeader('Content-Length');
        } else {
            $this->setHeader('Content-length', strlen($this->content));
        }
    }

    /**
     * Create a new response with Content-Type: json
     *
     * @param array $data
     * @return self
     */
    public static function json(array $data): self
    {
        return (new self())
            ->setContentType('application/json')
            ->setContent(json_encode($data));
    }

    /**
     * Create a new response with Content-Type: text/plain
     *
     * @param string $text
     * @return self
     */
    public static function text(string $text): self
    {
        return (new self())
            ->setContentType('text/plain')
            ->setContent($text);
    }

    /**
     * Redirect to another URL or URI
     *
     * @param string $uri
     * @return self
     */
    public static function redirect(string $uri): self
    {
        return (new self())
            ->setStatus(302)
            ->setHeader('Location', $uri);
    }

    public static function view(string $view, array $parameters=[], string $layout=null): self
    {
        $content = app(ViewEngine::class)->render($view, $parameters, $layout);

        return (new self())
                    ->setStatus(200)
                    ->setHeader('Content-Type', 'text/html; charset=utf-8')
                    ->setContentType('text/html')
                    ->setContent($content);
    }

    public function withErrors(array $errors, int $status=400): self
    {
        $this->setStatus($status);
        session()->flash('_errors', $errors);
        session()->flash('_old', request()->data());
        return $this;
    }
}
