<?php
require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);
$ExchangeTransfers = new \SteemTools\Reporting\ExchangeTransfers($api);

$start = '2016-08-14';
$end = '2016-08-21';

/*
$result = $ExchangeTransfers->getAccountTransferHistory('dantheman', $start, $end);
$result = $ExchangeTransfers->getTransfers($result);
var_dump($result);
*/

$ExchangeTransfers->runReport($start, $end);

//$ExchangeTransfers->runDailyReport($start, $end);
