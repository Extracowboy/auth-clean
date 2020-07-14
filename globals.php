<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'test');

session_start();

function loggedIn()
{
    return !empty($_SESSION['loggedIn']);
}
