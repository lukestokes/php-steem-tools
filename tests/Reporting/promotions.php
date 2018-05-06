<?php
setlocale(LC_MONETARY, 'en_US');
//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');

require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);

$start = '2016-08-27';
$end = '2016-09-02';


$transfers = $api->getAccountHistoryFiltered('null',array('transfer'),$start,$end);
$transfers = $api->getOpData($transfers);

$promotions = array();

foreach ($transfers as $transfer) {
    if (!array_key_exists($transfer['memo'], $promotions)) {
        $promotions[$transfer['memo']] = array(
            'bid_count' => 0,
            'bid_total' => 0.0,
            'permlink' => $transfer['memo'],
            'bidders' => array()
            );
    }
    if (strpos($transfer['amount'], 'SBD') !== false) {
        $amount = str_replace(' SBD', '', $transfer['amount']);
        $promotions[$transfer['memo']]['bid_count'] += 1;
        $promotions[$transfer['memo']]['bid_total'] += $amount;
        if (!array_key_exists($transfer['from'], $promotions[$transfer['memo']]['bidders'])) {
            $promotions[$transfer['memo']]['bidders'][$transfer['from']] = 0.0;
        }
        $promotions[$transfer['memo']]['bidders'][$transfer['from']] += $amount;
    }

}

/* Code for getting content details of individual posts, such as the last payout. */
/*
foreach ($promotions as $permlink => $promotion) {
    $author_and_link = $api->getAuthorAndPermLink($permlink);
    $author_and_link['author'] = str_replace("@", "", $author_and_link['author']);
    $content = $api->getContent(
        array(
            $author_and_link['author'],
            $author_and_link['permlink']
            )
        );
    if ($content['last_payout'] != '1970-01-01T00:00:00') {
        print $content['last_payout'] . "," . $author_and_link['author'] . "," . $author_and_link['permlink'] . "," . $promotion['bid_total'] . "\n";
    }
}
*/


function cmp($a, $b) {
    if ($a['bid_total'] == $b['bid_total']) {
        return 0;
    }
    return ($a['bid_total'] > $b['bid_total']) ? -1 : 1;
}

usort($promotions, "cmp");

$header = "|    |  Amount |  Bids | Bidders | Post |\n";
$header .= "|:--:|:----------------:|:----------:|:----------:|:----------:|\n";
$body = '';

$number_to_show = 100;
$analysis = array();
for ($i = 0; $i < 125; $i = $i+5) {
    $analysis[$i] = 0;
}

$count = 0;
foreach ($promotions as $key => $promotion) {
    $count++;
    if ($count <= $number_to_show) {
        $body .= '| ' . $count . ' | ' . $promotion['bid_total'] . ' | ' . $promotion['bid_count'] . ' | ';
        foreach ($promotion['bidders'] as $bidder => $amount) {
            $body .= $bidder . ': ' . $amount . "<br />";
        }
        $body .= '| ' . $promotion['permlink'] . ' |' . "\n";
    }
    foreach ($analysis as $key => $value) {
        if ($promotion['bid_total'] >= $key && $promotion['bid_total'] < $key+5) {
            $analysis[$key] += 1;
        }
    }
}

print $header . $body;

print "\n\n";


print "Bid Amount,Number of Bids\n";
foreach ($analysis as $key => $value) {
    print $key . "-" . ($key+5) . "," . $value . "\n";
}

//var_dump($analysis);
