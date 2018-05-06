<?php
setlocale(LC_MONETARY, 'en_US');
//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');

require '../../vendor/autoload.php';

$account = 'lukestokes';

$steemd = new \SteemTools\Library\steemd('https://node.steem.ws');
$votes = $steemd->getAccountVotes($account);

$vote_history = array();

print '<body>';
print count($votes) . ' votes returned.<br />Collecting information on each vote.<br /><br />';

$votes_to_show = 9999;
$votes_collected = 0;
foreach($votes as $vote) {
    if (strpos($vote['authorperm'], '/re-') === false) {
        $votes_collected++;
        list($author,$permlink,$original) = explode('/',$vote['authorperm'], 3);
        $content = $steemd->getContent($author,$permlink);
        $vote_history[] = array(
            'author' => $content['author'],
            'url' => $content['url'],
            'title' => $content['title'],
            'vote_time' => $vote['time'],
            );
        print '.';
        if ($votes_collected >= $votes_to_show) {
            break;
        }
    }
}

sortBy('vote_time',$vote_history);

function sortBy($field, &$array, $direction = 'desc')
{
    usort($array, create_function('$a, $b', '
        $a = $a["' . $field . '"];
        $b = $b["' . $field . '"];

        if ($a == $b)
        {
            return 0;
        }

        return ($a ' . ($direction == 'desc' ? '>' : '<') .' $b) ? -1 : 1;
    '));
}

print '<table>';
print '<tr>';
print '<th>Author</th>';
print '<th>Title</th>';
print '<th>Vote Time</th>';
print '</tr>';
foreach ($vote_history as $record) {
    print '<tr>';
    print '<td>';
    print '<a href="https://steemit.com/@' . $record['author'] . '">@' . $record['author'] . '</a>';
    print '</td>';
    print '<td>';
    print '<a href="' . $record['url'] . '">' . $record['title'] . '</a>';
    print '</td>';
    print '<td>';
    print $record['vote_time'];
    print '</td>';
    print '<tr>';
}
print '</table>';
print '</body>';
