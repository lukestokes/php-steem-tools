<?php
include 'bootstrap.php';
include 'functions.php';

$start_exchange_rate = getExchangeRate($debug);
$vests = getVESTS($user_name, $debug);
$starting_balance = convertToSteemPower($vests, $start_exchange_rate);

if ($debug) {
    print " " . time() . ": Starting Steem Power balance: " . $starting_balance . "\n";
}

$sleep_time_in_minutes = 1;
$s = ($sleep_time_in_minutes > 1) ? 's' : '';
print "Sleeping $sleep_time_in_minutes minute$s...\n";
for($i=0; $i<$sleep_time_in_minutes; $i++) {
    print ".";
    sleep(60);
}
print "\n";

$end_exchange_rate = getExchangeRate($debug);
$vests = getVESTS($user_name, $debug);
$ending_balance = convertToSteemPower($vests, $end_exchange_rate);

if ($debug) {
    print " " . time() . ": Ending Steem Power balance: " . $ending_balance . "\n";
}

$change = $ending_balance - $starting_balance;
$steem_power_per_hour = ($change / $sleep_time_in_minutes) * 60;

$interest_rate_per_hour = number_format($steem_power_per_hour / $ending_balance * 100, 4);

print "--------------------------------\n";
print "Interest Rate Per Hour: $interest_rate_per_hour%\n";
print "Steem Power Per Hour: $steem_power_per_hour\n";
print "Interest Rate Per Week: " . ($interest_rate_per_hour * 24 * 7) . "%\n";
print "Steem Power Per Week: " . ($steem_power_per_hour * 24 * 7) . "\n";
