<?php

namespace Chomsky\Providers;
use Chomsky\View\StencilEngine;
use Chomsky\View\ViewEngine;

class ViewServiceProvider implements ServiceProvider
{
    public function registerServices(){
        match(config("view.engine", "stencil")){
            "stencil" => singleton(ViewEngine::class, fn()=>new StencilEngine(config("view.path")))
        };
    }

}
