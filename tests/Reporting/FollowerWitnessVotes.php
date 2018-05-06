<?php
require '../../vendor/autoload.php';

$params = array('debug' => true);
$params = array();

$SteemServiceLayer = new \SteemTools\SteemServiceLayer($params);
$api = new \SteemTools\SteemAPI($SteemServiceLayer);

$witness_account = "lukestokes.mhth";
$controller_account = "lukestokes";

$followers = $api->cachedCall('getFollowers', $controller_account, true);
$accounts = array();
foreach($followers as $follower) {
    $accounts[] = $follower['follower'];
}
$account_records = $api->cachedCall('getAccounts', $accounts, true);
$accounts_by_vest = array();
$accounts = array();
foreach($account_records as $account) {
    $accounts[$account['name']] = $account;
    $accounts_by_vest[$account['name']] = (int) str_replace(' VESTS', '', $account['vesting_shares']);
}

arsort($accounts_by_vest);
$top_accounts = array_slice($accounts_by_vest,0,30);

function showPossibilities($top_accounts,$accounts,$witness_account,$show_available_only = false)
{
    $available_vests = 0;
    foreach($top_accounts as $account_name => $vests) {
        $print_account = true;
        $witnesses_voted_for = $accounts[$account_name]['witnesses_voted_for'];
        $proxy = $accounts[$account_name]['proxy'];
        $witness_votes = $accounts[$account_name]['witness_votes'];
        $voted = " Already Voted? ";
        $include_vests = true;
        if (in_array($witness_account, $witness_votes)) {
            $voted .= "YES";
            $print_account = !$show_available_only;
            $include_vests = false;
        } else {
            $voted .= "NO";
        }
        $free_votes = (30 - $witnesses_voted_for);
        if ($print_account && $show_available_only) {
            $print_account = ($free_votes > 0);
        }
        if ($print_account && $show_available_only) {
            $print_account = !($proxy != '');
        }
        $pad_amount = 20;
        if ($print_account) {
            if ($include_vests) {
                $available_vests += $accounts[$account_name]['vesting_shares'];
            }
            print str_pad($account_name,$pad_amount,' ',STR_PAD_RIGHT);
            print str_pad('Proxy: ' . $proxy,$pad_amount,' ',STR_PAD_RIGHT);
            print str_pad($voted,$pad_amount,' ',STR_PAD_RIGHT);
            print str_pad($vests . " VESTS",$pad_amount,' ',STR_PAD_LEFT);
            print str_pad($free_votes . " votes free",$pad_amount,' ',STR_PAD_LEFT);
            print "\n";
        }
    }
    print "Total Available MVESTS: " . round($available_vests/1000000) . "\n";
    print "\n";
}

showPossibilities($top_accounts,$accounts,$witness_account);
showPossibilities($top_accounts,$accounts,$witness_account,true);
