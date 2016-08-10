<?php
$debug = false;

$apis = array();

/*
curl https://node.steem.ws -d '{"jsonrpc":"2.0","method":"call", "params":[1, "get_api_by_name", ["follow_api"]],"id":0}'
{"id":0,"result":3}                                                                                                                     curl https://node.steem.ws -d '{"jsonrpc":"2.0","method":"call", "params":[3, "get_following", ["lukestokes",0,1]],"id":0}'
{"id":0,"result":[{"id":"8.6.59312","follower":"lukestokes","following":"acidyo","what":["blog"]}]}
*/

printTopFollowed();

function printTopFollowed() {
    $number_of_top_accounts_to_show = 100;
    $accounts = array();
    $file = fopen("saveFollowerCounts_counts.txt","r");
    while(!feof($file)) {
        $line = fgetcsv($file);
        $accounts[trim($line[0])] = trim($line[1]);
    }
    fclose($file);

    arsort($accounts);

    $header = "|    |           Account|    Number of Followers   | \n";
    $header .= "|:--:|:----------------:|:------------------------:|\n";

    print "\n## <center>TOP $number_of_top_accounts_to_show USERS BY FOLLOWER COUNT </center>\n\n";
    print $header;
    $count = 0;
    foreach ($accounts as $account => $follower_count) {
        $count++;
        if ($count > $number_of_top_accounts_to_show) {
            break;
        }
        print '|  ' . $count . '  |' . sprintf('%15s','@'.$account) . ': |   ' . $follower_count . "   |\n";
    }
}


function saveFollowerCounts() {
    $min_threshold = 0;
    $follower_counts = array();
    // $all_accounts = getAllAccounts();
    $all_accounts = @file('getAllAccountsWithPosts_accounts.txt');
    if (!$all_accounts) {
        getAllAccountsWithPosts();
        $all_accounts = file('getAllAccountsWithPosts_accounts.txt');
    }
    $start = @file_get_contents('saveFollowerCounts_start.txt');
    if (!$start) {
        $start = 0;
        file_put_contents('saveFollowerCounts_start.txt',$start);
    }
    print "Starting at $start\n";
    for ($i = $start; $i<count($all_accounts); $i++) {
        $account = trim($all_accounts[$i]);
        if ($i % 100 == 0) {
            print $i;
        }
        print '.';
        $follower_count = getFollowerCount($account);
        file_put_contents('saveFollowerCounts_start.txt',$i);
        if ($follower_count > $min_threshold) {
            file_put_contents('saveFollowerCounts_counts.txt', $account . ',' . $follower_count . "\n", FILE_APPEND);
        }
    }
}


function getAllAccountsWithPosts() {
    $all_accounts = getAllAccounts();
    $total = count($all_accounts);
    $start = @file_get_contents('getAllAccountsWithPosts_start.txt');
    if (!$start) {
        $start = 0;
        file_put_contents('getAllAccountsWithPosts_start.txt',$start);
    }
    $batch_size = 100;
    while($total > $batch_size) {
        file_put_contents('getAllAccountsWithPosts_start.txt',$start);
        $filtered_accounts = getAccountsWithPosts($all_accounts, $start, $batch_size);
        $start += $batch_size;
        $total -= $batch_size;
        print '.';
        foreach ($filtered_accounts as $filtered_account) {
            file_put_contents('getAllAccountsWithPosts_accounts.txt', $filtered_account . "\n", FILE_APPEND);
        }
    }
    $start -= $batch_size;
    $filtered_accounts = getAccountsWithPosts($all_accounts, $start, $total);
    print '.';
    foreach ($filtered_accounts as $filtered_account) {
        file_put_contents('getAllAccountsWithPosts_accounts.txt', $filtered_account . "\n", FILE_APPEND);
    }
}

function getAccountsWithPosts($all_accounts, $start, $batch_size) {
    $some_accounts = array_slice($all_accounts,$start,$batch_size);
    $accounts_with_info = call('get_accounts', array($some_accounts));
    $active_accounts = filterForActiveAccounts($accounts_with_info);
    $account_account_names = array();
    return $active_accounts;
}

function filterForActiveAccounts($accounts) {
    $filtered_accounts = array();
    foreach($accounts as $account) {
        if ($account['post_count'] > 0) {
            $filtered_accounts[] = $account['name'];
        }
    }
    return $filtered_accounts;
}

function getAllAccounts() {
    $all_accounts = @file_get_contents('all_accounts.txt');
    if ($all_accounts) {
        $all_accounts = unserialize($all_accounts);
        print "Found " . count($all_accounts) . " accounts.\n";
        return $all_accounts;
    }
    $all_accounts = call('lookup_accounts', array('*',-1));
    print "Queried for " . count($all_accounts) . " accounts.\n";
    file_put_contents('all_accounts.txt',serialize($all_accounts));
    return $all_accounts;
}

function getFollowerCount($account) {
    $followers = getFollowers($account);
    return count($followers);
}

function getFollowers($account, $start = '') {
    $limit = 100;
    $followers = array();
    $followers = call('call', array(getFollowAPIID(),'get_followers',array($account,$start,$limit)));
    if (count($followers) == $limit) {
        $last_account = $followers[$limit-1];
        $more_followers = getFollowers($account, $last_account['follower']);
        array_pop($followers);
        $followers = array_merge($followers, $more_followers);
    }
    return $followers;
}

function getFollowAPIID() {
    return getAPIID('follow_api');
}

function getAPIID($api_name) {
    global $apis;
    if (array_key_exists($api_name, $apis)) {
        return $apis[$api_name];
    }
    $response = call('call', array(1,'get_api_by_name',array('follow_api')));
    $apis[$api_name] = $response;
    return $response;
}


function call($method, $params) {
    global $debug;
    $request = getRequest($method, $params);
    $response = curl($request);
    if (array_key_exists('error', $response)) {
        var_dump($response['error']);
        die();
    }
    return $response['result'];
}

function getRequest($method, $params) {
    global $debug;
    $request = array(
        "jsonrpc" => "2.0",
        "method" => $method,
        "params" => $params,
        "id" => 0
        );
    $request_json = json_encode($request);

    if ($debug) { print $request_json . "\n"; }

    return $request_json;
}

function curl($data) {
    global $debug;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://node.steem.ws');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($ch);

    if ($debug) { print $result . "\n"; }

    $result = json_decode($result, true);

    return $result;
}
