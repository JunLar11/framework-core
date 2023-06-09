<?php

namespace Chomsky\Cryptic;

interface Hasher
{
    public function hash(string $input): string;
    public function verify(string $input, string $hashed): bool;
}
