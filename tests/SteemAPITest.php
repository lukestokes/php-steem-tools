<?php
require '../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();

$api = new \SteemTools\SteemAPI($SteemServiceLayer);

$result = $api->getCurrentMedianHistoryPrice();

var_dump($result);
