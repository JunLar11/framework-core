<?php

namespace Chomsky\Auth\Authenticators;

use Chomsky\Auth\Authenticatable;

interface Authenticator
{
    public function login(Authenticatable $subject);
    public function logout(Authenticatable $subject);
    public function isAuthenticated(Authenticatable $subject):bool;
    public function resolve():?Authenticatable;


}
