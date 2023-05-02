<?php

use Chomsky\Auth\Auth;
use Chomsky\Auth\Authenticatable;

function auth():?Authenticatable{
    return Auth::user();
}
function isGuest():bool{
    return Auth::isGuest();
}
