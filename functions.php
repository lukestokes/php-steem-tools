<?php
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
    curl_setopt($ch, CURLOPT_URL, 'https://this.piston.rocks');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);
    /*
    if ($debug) {
        print "Result from pison.rocks:\n";
        print "$result\n";
    }
    */
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