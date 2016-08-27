<?php
require '../../vendor/autoload.php';

$params = array('debug' => true);
$params = array();

$SteemServiceLayer = new \SteemTools\SteemServiceLayer($params);
$api = new \SteemTools\SteemAPI($SteemServiceLayer);
$FollowerStats = new \SteemTools\Reporting\FollowerStats($api);

//$result = $FollowerStats->getAllAccounts();
//var_dump(count($result));

//$FollowerStats->printTopFollowed();

$FollowerStats->compareTwoReports('saveFollowerCounts_data.txt','saveFollowerCounts_data_2016-08-18.txt');


