<?php
include 'bootstrap.php';
include 'functions.php';

$interval = 5;
$filename = 'balance_over_time_data_' . $user_name . '.txt';
print "Writing to $filename every $interval minutes...\n";
while (true) {
    $file = fopen($filename,'a');
    $start_exchange_rate = getExchangeRate($debug);
    $vests = getVESTS($user_name, $debug);
    $balance = convertToSteemPower($vests, $start_exchange_rate);
    $line = time() . ',' . date('c') . ',' . $balance . "\n";
    fwrite($file,$line);
    fclose($file);
    print ".";
    sleep($interval * 60); // sleep five minutes
}