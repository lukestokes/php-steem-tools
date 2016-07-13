<?php
date_default_timezone_set('America/Chicago');
$debug = false;

if (!isset($argv[1])) {
    exit("When you run this script, please include your username as a command line argument.\n");
}

$user_name = $argv[1];

if (isset($argv[2]) && $argv[2] == 'true') {
    $debug = true;
}
