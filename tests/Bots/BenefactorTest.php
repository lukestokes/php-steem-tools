<?php
require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer();
$api = new \SteemTools\SteemAPI($SteemServiceLayer);

$config = array(
    'follow_vote_comments' => false,
    'looptime_in_seconds' => 1,
    'reminder_in_seconds' => 3,
    'auto_vote' => true
);

$Benefactor = new \SteemTools\Bots\Benefactor($api, $config);

$account = 'lukestokes';
$benefactor_account = 'robinhoodwhale';

//$result = $Benefactor->getLastVote($account);

$Benefactor->run($account,$benefactor_account);
