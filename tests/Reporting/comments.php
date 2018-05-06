<?php
setlocale(LC_MONETARY, 'en_US');
//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');

require '../../vendor/autoload.php';

$SteemServiceLayer = new \SteemTools\SteemServiceLayer(array('debug' => false));
$api = new \SteemTools\SteemAPI($SteemServiceLayer);
$comments = $api->getAccountHistoryFiltered('jedau', array('comment'), '2016-08-01', '2016-08-30');
$comments = $api->getOpData($comments);
foreach($comments as $comment) {
    if ($comment['parent_author'] == 'lukestokes') {
        var_dump($comment);
    }
}
