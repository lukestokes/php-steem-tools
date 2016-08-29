<?php
require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);
$CopyCatVoter = new \SteemTools\Bots\CopyCatVoter($api);

$CopyCatVoter->currationReport('lukestokes');
