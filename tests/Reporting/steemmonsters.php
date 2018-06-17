<?php

// from https://steemmonsters.com/cards/get_details
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
$account = 'steemmonsters';
$limit = 2000;
$start = '2018-05-01';
$end = '2018-06-17';

$params = array($account, -1, $limit);
$result = $api->getAccountHistory($params);
//$result = $api->cachedCall('getAccountHistory', $params, true);
$info = getResultInfo($result);
$start_timestamp = strtotime($start);
$end_timestamp = strtotime($end);
$filtered_results = filterAccountHistory($result,$start_timestamp,$end_timestamp,array('custom_json'));
//var_dump($start_timestamp);
//var_dump($info['min_timestamp']);
while ($start_timestamp < $info['min_timestamp']) {
    $from = $info['min_id'];
    if ($limit > $from) {
        $limit = $from;
    }
	//var_dump($from);
	//var_dump($limit);
    if ($limit == $from && $from == 0) {
        break;
    }
    $params = array($account, $info['min_id'], $limit);
    $result = $api->getAccountHistory($params);
    //$result = $api->cachedCall('getAccountHistory', $params, true);
    $filtered_results = array_merge(
        $filtered_results,
        filterAccountHistory($result,$start_timestamp,$end_timestamp,array('custom_json'))
        );
    $info = getResultInfo($result);
	//var_dump($start_timestamp);
	//var_dump($info['min_timestamp']);
}

$custom_json_blocks = $filtered_results;
$custom_json_data = getCustomJsonData($custom_json_blocks);
$pack_data = filterBySMJsonId($custom_json_data,'generate_packs');
$accounts = array();
$card_count = array();
foreach ($pack_data as $i => $data) {
	//print '.';
	if (!array_key_exists($data['account'], $accounts)) {
		$accounts[$data['account']] = array('total' => 0);
	}
	foreach ($data['packs'] as $i => $pack_data) {
		foreach ($pack_data[1] as $i => $card_data) {
			if (!array_key_exists($card_data[0], $card_count)) {
				$card_count[$card_data[0]] = 0;
			}
			$card_count[$card_data[0]]++;
			if (!array_key_exists($card_data[0], $accounts[$data['account']])) {
				$accounts[$data['account']][$card_data[0]] = 0;
			}
			$accounts[$data['account']][$card_data[0]]++;
			$accounts[$data['account']]['total']++;
		}
	}
	ksort($accounts[$data['account']]);
}

ksort($card_count);

//var_dump($accounts);

uasort($accounts, function($a, $b) {
    return $a['total'] - $b['total'];
});

$total_purchasers = count($accounts);

print "Top 50 Most Addicted Accounts\n";
print "| Addiction Rank | Account | Card Purchases | \n";
print "|:----------------:|:----------------:|:------------------------:|\n";

for($i = 1; $i <= 50; $i++) {
	$keys = array_keys($accounts);
	$last_account = array_pop($keys);
	print "|" . $i . "|" . $last_account . "|" . number_format($accounts[$last_account]['total']) . "|\n";
	array_pop($accounts);
}

$i = 50;
while (count($accounts) > 0) {
	$i++;
	$keys = array_keys($accounts);
	$last_account = array_pop($keys);
	if ($last_account == 'lukes.random') {
		print "\nI'm currently the " . $i . " most addicted steemmonsters user out of " . $total_purchasers . "\n";
		break;
	}
	array_pop($accounts);
}

//array_values($sorted_accounts)[0];

//var_dump($sorted_accounts[0])

//var_dump($accounts['lukes.random']);
//var_dump($card_count);

$rarities = array(1 => 'Common', 2 => 'Rare', 3 => 'Epic', 4 => 'Legendary');

foreach ($steem_monsters as $rarity => $group) {
	print '<h2>' . $rarities[$rarity] . "</h2>\n";
    print "| Card | Total in Existence   | \n";
    print "|:----------------:|:------------------------:|\n";
	foreach ($group as $id => $name) {
		print '|'. $steem_monsters[$rarity][$id] . ' | ' . number_format($card_count[$id]) . "|\n";
	}
}


//var_dump($custom_json_data);

/*
$unique_ids = array();
foreach ($custom_json_data as $i => $data) {
	if (!in_array($data['id'], $unique_ids)) {
		$unique_ids[] = $data['id'];
	}
}
var_dump($unique_ids);
array(8) {
  [0]=>
  string(14) "combine_result"
  [1]=>
  string(14) "generate_packs"
  [2]=>
  string(10) "gift_packs"
  [3]=>
  string(10) "gift_cards"
  [4]=>
  string(21) "generate_starter_pack"
  [5]=>
  string(13) "sm_gift_cards"
  [6]=>
  string(13) "sm_gift_packs"
  [7]=>
  string(6) "follow"
}
*/



//////////////////////////////////////////////////////////////////////

function getResultInfo($blocks)
{
    $max_timestamp = 0;
    $min_timestamp = 0;
    $max_id = 0;
    $min_id = 0;
    foreach($blocks as $block) {
        $timestamp = strtotime($block[1]['timestamp']);
        if ($timestamp >= $max_timestamp) {
            $max_timestamp = $timestamp;
            $max_id = $block[0];
        }
        if (!$min_timestamp) {
            $min_timestamp = $max_timestamp;
        }
        if ($timestamp <= $min_timestamp) {
            $min_timestamp = $timestamp;
            $min_id = $block[0];
        }
    }
    return array(
            'max_id' => $max_id,
            'max_timestamp' => $max_timestamp,
            'min_id' => $min_id,
            'min_timestamp' => $min_timestamp
        );
}

function filterBySMJsonId($custom_json_data, $id)
{
	$sm_json_data = array();
	foreach ($custom_json_data as $i => $data) {
		//print '.';
		if ($data['id'] == $id) {
			$sm_json_data[] = json_decode($data['json'],true);
		}
	}
	return $sm_json_data;
}

function getCustomJsonData($result)
{
    $custom_json_data = array();
    if (count($result)) {
        foreach($result as $block) {
        	$custom_json_data[] = $block[1]['op'][1];
        }
    }
    return $custom_json_data;
}


function filterAccountHistory($result, $start_timestamp, $end_timestamp, $ops = array())
{
    // globals:
    $min_timestamp = time();
    $max_timestamp = 0;
    $filtered_results = array();
    if (count($result)) {
        foreach($result as $block) {
            $timestamp = strtotime($block[1]['timestamp']);
            if (in_array($block[1]['op'][0], $ops)
                && $timestamp >= $start_timestamp
                && $timestamp <= $end_timestamp
                ) {
                $min_timestamp = min($min_timestamp,$timestamp);
                $max_timestamp = max($max_timestamp,$timestamp);
                $filtered_results[] = $block;
            }
        }
    }
    return $filtered_results;
}

