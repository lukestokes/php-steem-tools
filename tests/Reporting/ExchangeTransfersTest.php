<?php
require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);
$ExchangeTransfers = new \SteemTools\Reporting\ExchangeTransfers($api);

$start = '2016-08-21';
$end = '2016-08-28';

print "Begin time: " . date('c') . "\n";

$ExchangeTransfers->runReport($start, $end);
//$ExchangeTransfers->runHourlyReport($start, $end);

print "End time: " . date('c') . "\n";

