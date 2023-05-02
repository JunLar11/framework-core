<?php

namespace Chomsky\Storage\Drivers;

class DiskFileStorage implements FileStorageDriver
{
    protected string $storageDirectory;
    
    protected string $storageUri;

    protected string $appUrl;

    public function __construct(string $storageDirectory, string $storageUri, string $appUrl) {
        $this->storageDirectory = $storageDirectory;
        $this->storageUri = $storageUri;
        $this->appUrl = $appUrl;
    }
    public function put(string $path, mixed $content): string {
        if (!is_dir($this->storageDirectory)) {
            mkdir($this->storageDirectory);
        }

        $directories = explode("/", $path);
        $file = array_pop($directories);
        $dir = "$this->storageDirectory/";

        if (count($directories) > 0) {
            $dir = $this->storageDirectory . "/" . implode("/", $directories);
            @mkdir($dir, recursive: true);
        }

        file_put_contents("$dir/$file", $content);

        return "$this->appUrl/$this->storageUri/$path";
    }
}
