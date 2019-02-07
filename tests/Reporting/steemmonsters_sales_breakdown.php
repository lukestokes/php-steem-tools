<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$steem_monster_data_json = '[{
	"id": 2,
	"name": "Giant Roc",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 3,
	"name": "Kobold Miner",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 4,
	"name": "Fire Beetle",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 7,
	"name": "Pit Ogre",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 8,
	"name": "Cerberus",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 12,
	"name": "Pirate Captain",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 14,
	"name": "Crustacean King",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 15,
	"name": "Sabre Shark",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 17,
	"name": "Medusa",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 19,
	"name": "Frozen Soldier",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 23,
	"name": "Flesh Golem",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 26,
	"name": "Minotaur Warrior",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 27,
	"name": "Lyanna Natura",
	"color": "Green",
	"type": "Summoner",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 28,
	"name": "Earth Elemental",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 30,
	"name": "Stonesplitter Orc",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 36,
	"name": "Silvershield Knight",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 37,
	"name": "Silvershield Warrior",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 38,
	"name": "Tyrus Paladium",
	"color": "White",
	"type": "Summoner",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 39,
	"name": "Peacebringer",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 40,
	"name": "Silvershield Paladin",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 45,
	"name": "Animated Corpse",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 47,
	"name": "Skeleton Assassin",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 48,
	"name": "Spineback Wolf",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": true
}, {
	"id": 51,
	"name": "Twisted Jester",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 52,
	"name": "Undead Priest",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": true
}, {
	"id": 50,
	"name": "Haunted Spirit",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 18,
	"name": "Water Elemental",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 29,
	"name": "Stone Golem",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 6,
	"name": "Serpentine Soldier",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 41,
	"name": "Clay Golem",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 46,
	"name": "Haunted Spider",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 13,
	"name": "Spineback Turtle",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 24,
	"name": "Goblin Sorcerer",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 1,
	"name": "Goblin Shaman",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 35,
	"name": "Feral Spirit",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 25,
	"name": "Rexxie",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 34,
	"name": "Divine Healer",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 1,
	"drop_rate": 80,
	"stats": null,
	"is_starter": false
}, {
	"id": 5,
	"name": "Malric Inferno",
	"color": "Red",
	"type": "Summoner",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 16,
	"name": "Alric Stormbringer",
	"color": "Blue",
	"type": "Summoner",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 49,
	"name": "Zintar Mortalis",
	"color": "Black",
	"type": "Summoner",
	"sub_type": null,
	"rarity": 2,
	"drop_rate": 22,
	"stats": null,
	"is_starter": false
}, {
	"id": 9,
	"name": "Fire Demon",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 10,
	"name": "Serpent of the Flame",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 20,
	"name": "Mischievous Mermaid",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 21,
	"name": "Naga Warrior",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 31,
	"name": "Magi of the Forest",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 32,
	"name": "Swamp Thing",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 42,
	"name": "Defender of Truth",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 43,
	"name": "Air Elemental",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 53,
	"name": "Enchantress of the Night",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 54,
	"name": "Screaming Banshee",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 3,
	"drop_rate": 10,
	"stats": null,
	"is_starter": false
}, {
	"id": 11,
	"name": "Elemental Phoenix",
	"color": "Red",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 22,
	"name": "Frost Giant",
	"color": "Blue",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 33,
	"name": "Spirit of the Forest",
	"color": "Green",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 44,
	"name": "Angel of Light",
	"color": "White",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 55,
	"name": "Lord of Darkness",
	"color": "Black",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 56,
	"name": "Selenia Sky",
	"color": "Gold",
	"type": "Summoner",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 57,
	"name": "Lightning Dragon",
	"color": "Gold",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 58,
	"name": "Chromatic Dragon",
	"color": "Gold",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}, {
	"id": 59,
	"name": "Gold Dragon",
	"color": "Gold",
	"type": "Monster",
	"sub_type": null,
	"rarity": 4,
	"drop_rate": 2,
	"stats": null,
	"is_starter": false
}]';

$steem_monster_data = json_decode($steem_monster_data_json,true);
$steem_monsters = array(1 => array(), 2 => array(), 3 => array(), 4 => array());
foreach ($steem_monster_data as $i => $data) {
	$steem_monsters[$data['rarity']][$data['id']] = $data['name'];
}

require '../../vendor/autoload.php';
ini_set('memory_limit', '1G');
$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);


$filename = 'cache/sales_34d6d423242241b50ebfe745247b3963_data.txt';
$sales = file_get_contents($filename);
$sales = unserialize($sales);

$cards = array();
foreach ($sales as $trade) {
	$cards[] = $trade['card'];
}

// get details
print "Number of cards: " . count($cards) . "\n";

$get_fresh_card_data = false;

if ($get_fresh_card_data) {
	$url = "https://steemmonsters.com/cards/find?ids=";

	$cards_copy = $cards;

	$number_of_cards = count($cards);

	$all_trade_data = array();

	while ($number_of_cards > 0) {
		$batch = array();
		for ($i=$number_of_cards; $i > ($number_of_cards - 200); $i--) { 
			if (isset($cards_copy[$i])) {
				$batch[] = $cards_copy[$i];
			}
		}
		$trade_data = file_get_contents($url . implode(",",$batch));
		$trade_data = json_decode($trade_data, true);
		$all_trade_data = array_merge($all_trade_data, $trade_data);
		$number_of_cards -= 200;
	}

	$trade_data = $all_trade_data;	

	$trade_data_for_writing_to_file = serialize($trade_data);
	$filename = 'cache/trades_' . md5($trade_data_for_writing_to_file) . '_data.txt';
	file_put_contents($filename,$trade_data_for_writing_to_file);
} else {
	$filename = 'cache/trades_141e458c72b9d39b452d4eab3c64e663_data.txt';
	$trade_data = file_get_contents($filename);
	$trade_data = unserialize($trade_data);
}


print "Result count : " . count($trade_data) . "\n";

$card_data = $trade_data;

$price_history = $api->getPriceHistoryInUSD('2018-07-14', '2018-07-19');

$cards = array();
$gold_cards = array();

$combined_cards = 0;


//var_dump($card_data[0]);
//exit();

$missing_cards = array();

function parseCardData($card_data, $sales, $price_history, $gold_cards, &$combined_cards, &$missing_cards) {
	$cards = array();
	foreach ($card_data as $card_details) {
		if (!isset($card_details['card_detail_id'])) {
			$combined_cards++;
			$missing_cards[] = $card_details['uid'];
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
				foreach ($sales as $trade_details) {
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


$cards = parseCardData($card_data,$sales,$price_history,false,$combined_cards,$missing_cards);
$gold_cards = parseCardData($card_data,$sales,$price_history,true,$combined_cards,$missing_cards);

print "## Gold Card Sales\n";

$header = "| Name | Sales Count | Total Sales | Average | Max | Min | \n";
$header .= "|:------:|:----------------:|:----------------:|:----------------:|:----------------:|:------------------------:|\n";

$rarities = array(1 => 'Common', 2 => 'Rare', 3 => 'Epic', 4 => 'Legendary');
$last_rarity = '';

foreach ($steem_monsters as $rarity => $group) {
	if ($last_rarity != $rarity) {
		print '<h2>' . $rarities[$rarity] . "</h2>\n";
		print $header;
		$last_rarity = $rarity;
	}
	foreach ($group as $group_card_id => $name) {
		foreach ($gold_cards as $id => $card) {
			if ($group_card_id == $id) {
				print "| " . $card['name'] . " | ";
				print $card['sale_count'] . " | ";
				print "$" . number_format($card['total_sales_usd'],2) . " | ";
				print "$" . number_format($card['average_sale_price'],2) . " | ";
				print "$" . number_format($card['highest_sale_price'],2) . " | ";
				print "$" . number_format($card['lowest_sale_price'],2) . " | ";
				print "\n";
			}		
		}
	}
}

print "## Regular Card Sales\n";

$header = "| Name | Sales Count | Total Sales | Average | Max | Min | \n";
$header .= "|:------:|:----------------:|:----------------:|:----------------:|:----------------:|:------------------------:|\n";

$last_rarity = '';

foreach ($steem_monsters as $rarity => $group) {
	if ($last_rarity != $rarity) {
		print '<h2>' . $rarities[$rarity] . "</h2>\n";
		print $header;
		$last_rarity = $rarity;
	}
	foreach ($group as $group_card_id => $name) {
		foreach ($cards as $id => $card) {
			if ($group_card_id == $id) {
				print "| " . $card['name'] . " | ";
				print $card['sale_count'] . " | ";
				print "$" . number_format($card['total_sales_usd'],2) . " | ";
				print "$" . number_format($card['average_sale_price'],2) . " | ";
				print "$" . number_format($card['highest_sale_price'],2) . " | ";
				print "$" . number_format($card['lowest_sale_price'],2) . " | ";
				print "\n";
			}		
		}
	}
}

print "\nThere were " . $combined_cards . " trades for cards that were combined after the trade so their information is gone.\n";

//var_dump($missing_cards);

