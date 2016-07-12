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

function convertToSteemPower($vests, $exchange_rate) {
    return (($vests / 1000000) * $exchange_rate);
}

function getVESTS($user_name, $debug)
{
    if ($debug) {
        print "Getting VESTS balance for $user_name via Piston...\n";
    }
    $result = array();
    exec('piston balance ' . $user_name, $result);

    $pattern = "% \| ([\d\.]*) VESTS%";
    $matches = array();
    preg_match($pattern, $result[3], $matches);
    $vests = $matches[1];
    if ($debug) {
        print "  VESTS: $vests...\n";
    }
    return $vests;
}

function getExchangeRate($debug)
{
    if ($debug) {
        print "Getting exchange rate at " . date('c') . "...\n";
    }
    $data = '{"jsonrpc":"2.0","method":"call", "params":[0, "get_dynamic_global_properties", [""]],"id":0}';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://this.piston.rocks');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    $result_json = json_decode($result);
    $exchange_rate = $result_json->result->total_vesting_fund_steem / $result_json->result->total_vesting_shares;
    $exchange_rate *= 1000000;
    if ($debug) {
        print "   Rate: 1 SP = 1M VESTS = " . $exchange_rate . "\n";
    }
    /*
    Previous approach to steemd is no good because these values aren't dynamic enough.

    $steemd_distribution = file_get_contents('https://steemd.com/distribution');
    $pattern = "%<code>1 SP = 1M VESTS = ([\d\.]*) STEEM = \\$([\d\.]*)<\/code>%";
    $matches = array();
    preg_match($pattern, $steemd_distribution, $matches);
    $exchange_rate = $matches[1];
    print "   Rate: 1 SP = 1M VESTS = " . $exchange_rate . "\n";
    */

    return $exchange_rate;
}

