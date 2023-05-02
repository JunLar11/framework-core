<?php

namespace Chomsky\Storage\Drivers;

interface FileStorageDriver
{
    public function put(string $path, mixed $content): string;
}
