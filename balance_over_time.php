<?php
include 'bootstrap.php';
include 'functions.php';

$interval = 5;

print "Writing to balance_over_time_data.txt every $interval minutes...\n";
while (true) {
    $file = fopen("balance_over_time_data.txt","w");
    $start_exchange_rate = getExchangeRate($debug);
    $vests = getVESTS($user_name, $debug);
    $balance = convertToSteemPower($vests, $start_exchange_rate);
    $line = time() . ',' . date('c') . ',' . $balance . "\n";
    fwrite($file,$line);
    fclose($file);
    print ".";
    sleep($interval * 60); // sleep five minutes
}