<?php
/*
$filename = 'cache/sales_73e29fd87398d7a473ce7832a6d00942_data.txt';
$data = file_get_contents($filename);
$data = unserialize($data);

$cards = array();
foreach ($data as $trade) {
	$cards[] = $trade['card'];
}

// get details
print "Number of cards: " . count($cards) . "\n";

$url = "https://steemmonsters.com/cards/find?ids=";
$url .= implode(",",$cards);
$trade_data = file_get_contents($url);
$trade_data = json_decode($trade_data,true);

print "Result count : " . count($trade_data) . "\n";

$trade_data_for_writing_to_file = serialize($trade_data);
$filename = 'cache/trades_' . md5($trade_data_for_writing_to_file) . '_data.txt';
file_put_contents($filename,$trade_data_for_writing_to_file);

*/

require '../../vendor/autoload.php';
ini_set('memory_limit', '1G');
$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);

$filename = 'cache/sales_73e29fd87398d7a473ce7832a6d00942_data.txt';
$data = file_get_contents($filename);
$trades = unserialize($data);

$filename = 'cache/trades_f4ad72a3c16fcb85d9a3097dac3e4b2d_data.txt';
$data = file_get_contents($filename);
$card_data = unserialize($data);

$price_history = $api->getPriceHistoryInUSD('2018-07-14', '2018-07-16');

$cards = array();
$gold_cards = array();

$combined_cards = 0;

function parseCardData($card_data, $trades, $price_history, $gold_cards, &$combined_cards) {
	$cards = array();
	foreach ($card_data as $card_details) {
		if ($card_details['card_detail_id'] == '') {
			$combined_cards++;
		} else {
			if ($card_details['gold'] == $gold_cards) {
				if (!array_key_exists($card_details['card_detail_id'], $cards)) {
					$cards[$card_details['card_detail_id']] = array(
						'name' => $card_details['details']['name'],
						'rarity' => $card_details['details']['rarity'],
						'is_starter' => $card_details['details']['is_starter'],
						'sale_count' => 0,
						'total_sales_usd' => 0,
						'average_sale_price' => 0,
						'highest_sale_price' => 0,
						'lowest_sale_price' => 9999999999999999,
						'trades' => array(),
					);
				}
				$trade = array();
				foreach ($trades as $trade_details) {
					if ($trade_details['card'] == $card_details['uid']) {
						$cards[$card_details['card_detail_id']]['sale_count']++;
						$usd_value = 0;
						if ($trade_details['sale_currency'] == 'STEEM') {
							$usd_value = ($price_history['average']['STEEM'] * $trade_details['sale_amount']);
						} else {
							$usd_value = ($price_history['average']['SBD'] * $trade_details['sale_amount']);
						}
						$cards[$card_details['card_detail_id']]['total_sales_usd'] += $usd_value;
						$cards[$card_details['card_detail_id']]['highest_sale_price'] = max($cards[$card_details['card_detail_id']]['highest_sale_price'],$usd_value);
						$cards[$card_details['card_detail_id']]['lowest_sale_price'] = min($cards[$card_details['card_detail_id']]['lowest_sale_price'],$usd_value);
						$cards[$card_details['card_detail_id']]['average_sale_price'] = $cards[$card_details['card_detail_id']]['total_sales_usd'] / $cards[$card_details['card_detail_id']]['sale_count'];
						$trade[] = $trade_details;
					}
				}
				$cards[$card_details['card_detail_id']]['trades'][] = $trade;
			}
		}
	}
	return $cards;
}


$cards = parseCardData($card_data,$trades,$price_history,false,$combined_cards);
$gold_cards = parseCardData($card_data,$trades,$price_history,true,$combined_cards);

print "## Gold Card Sales\n";

print "| card id | Name | Rarity | is_starter | Sales Count | Total Sales | Average | Max | Min | \n";
print "|:------:|:------:|:------:|:------:|:----------------:|:----------------:|:----------------:|:----------------:|:------------------------:|\n";

foreach ($gold_cards as $id => $card) {
	print "| " . $id . " | ";
	print $card['name'] . " | ";
	print $card['rarity'] . " | ";
	print ($card['is_starter'] ? 'true' : 'false') . " | ";
	print $card['sale_count'] . " | ";
	print "$" . number_format($card['total_sales_usd'],2) . " | ";
	print "$" . number_format($card['average_sale_price'],2) . " | ";
	print "$" . number_format($card['highest_sale_price'],2) . " | ";
	print "$" . number_format($card['lowest_sale_price'],2) . " | ";
	print "\n";
}

print "## Regular Card Sales\n";

print "| card id | Name | Rarity | is_starter | Sales Count | Total Sales | Average | Max | Min | \n";
print "|:------:|:------:|:------:|:------:|:----------------:|:----------------:|:----------------:|:----------------:|:------------------------:|\n";

foreach ($cards as $id => $card) {
	print "| " . $id . " | ";
	print $card['name'] . " | ";
	print $card['rarity'] . " | ";
	print ($card['is_starter'] ? 'true' : 'false') . " | ";
	print $card['sale_count'] . " | ";
	print "$" . number_format($card['total_sales_usd'],2) . " | ";
	print "$" . number_format($card['average_sale_price'],2) . " | ";
	print "$" . number_format($card['highest_sale_price'],2) . " | ";
	print "$" . number_format($card['lowest_sale_price'],2) . " | ";
	print "\n";
}

print "\nThere were " . $combined_cards . " trades for cards that were combined after the trade so their information is gone.\n";


