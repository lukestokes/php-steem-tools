<?php
namespace SteemTools\Reporting;

setlocale(LC_MONETARY, 'en_US');
date_default_timezone_set('UTC');


class FollowerStats
{
    public $SteemAPI;
    public $number_of_top_accounts_to_show = 1000;
    public $exploiters = array('steemlinks','freedomnow','steemitmarket','always1success','rook','arnoldwish','ivanba12','skyefox');

    public function __construct($SteemAPI, $config = array())
    {
        $this->SteemAPI = $SteemAPI;
        if (array_key_exists('number_of_top_accounts_to_show', $config)) {
            $this->number_of_top_accounts_to_show = $config['number_of_top_accounts_to_show'];
        }
    }


    public function getAllAccounts()
    {
        $params = array('*',-1);
        $all_accounts = $this->SteemAPI->cachedCall('lookupAccounts', $params, true);
        print "getAllAccounts: " . count($all_accounts) . "\n";
        return $all_accounts;
    }

    public function getAccountsWithPosts($all_accounts, $start, $batch_size)
    {
        $some_accounts = array_slice($all_accounts, $start, $batch_size);
        $accounts_with_info = $this->SteemAPI->getAccounts($some_accounts);
        $active_accounts = $this->filterForActiveAccounts($accounts_with_info);
        return $active_accounts;
    }

    public function filterForActiveAccounts($accounts)
    {
        $filtered_accounts = array();
        foreach($accounts as $account) {
            if ($account['post_count'] > 0) {
                $filtered_accounts[] = $account['name'];
            }
        }
        return $filtered_accounts;
    }

    public function getTotalActiveAccounts()
    {
        $file = "getAllAccountsWithPosts_data.txt";
        $lines = count(file($file));
        return $lines;
    }

    public function saveAllActiveAccountsWithPostsToAFile()
    {
        print "saveAllActiveAccountsWithPostsToAFile...\n";
        $all_accounts = $this->getAllAccounts();
        $total = count($all_accounts);
        $start = $this->getStartCountFromFile('getAllAccountsWithPosts');
        $batch_size = 100;
        while($total > $batch_size) {
            $this->saveStartCountToFile('getAllAccountsWithPosts', $start);
            $filtered_accounts = $this->getAccountsWithPosts($all_accounts, $start, $batch_size);
            $start += $batch_size;
            $total -= $batch_size;
            print '.';
            foreach ($filtered_accounts as $filtered_account) {
                file_put_contents('getAllAccountsWithPosts_data.txt', $filtered_account . "\n", FILE_APPEND);
            }
        }
        $start -= $batch_size;
        $filtered_accounts = $this->getAccountsWithPosts($all_accounts, $start, $total);
        print '.';
        foreach ($filtered_accounts as $filtered_account) {
            file_put_contents('getAllAccountsWithPosts_data.txt', $filtered_account . "\n", FILE_APPEND);
        }
        print "\n";
        print "Active Accounts (at least one post): " . $this->getTotalActiveAccounts() . "\n";
    }

    public function saveFollowerCounts() {
        print "saveFollowerCounts...\n";
        $min_threshold = 10;
        $follower_counts = array();
        $active_accounts = @file('getAllAccountsWithPosts_data.txt');
        if (!$active_accounts) {
            $this->saveStartCountToFile('getAllAccountsWithPosts', 0);
            $this->saveAllActiveAccountsWithPostsToAFile();
            $active_accounts = file('getAllAccountsWithPosts_data.txt');
        }
        $start = $this->getStartCountFromFile('saveFollowerCounts');
        if (!$start) {
            $start = 0;
            $this->saveStartCountToFile('saveFollowerCounts', $start);
        }
        print "saveFollowerCounts: Starting at $start\n";
        for ($i = $start; $i<count($active_accounts); $i++) {
            $account = trim($active_accounts[$i]);
            if ($i % 100 == 0) {
                print $i;
            }
            print '.';
            $follower_count = $this->SteemAPI->getFollowerCount($account);
            $this->saveStartCountToFile('saveFollowerCounts', $i);
            if ($follower_count >= $min_threshold) {
                file_put_contents('saveFollowerCounts_data.txt', $account . ',' . $follower_count . "\n", FILE_APPEND);
            }
        }
    }

    public function compareTwoReports($file1, $file2) {
        $file1 = @fopen($file1,"r");
        $file2 = @fopen($file2,"r");
        if (!$file1 || !$file2) {
            die("Files not found.");
        }

        $accounts1 = array();
        while(!feof($file1)) {
            $line = fgetcsv($file1);
            $accounts1[trim($line[0])] = trim($line[1]);
        }
        fclose($file1);
        foreach ($this->exploiters as $account) {
            if (isset($accounts1[$account])) {
                unset($accounts1[$account]);
            }
        }
        arsort($accounts1);

        $accounts2 = array();
        while(!feof($file2)) {
            $line = fgetcsv($file2);
            $accounts2[trim($line[0])] = trim($line[1]);
        }
        fclose($file2);
        foreach ($this->exploiters as $account) {
            if (isset($accounts2[$account])) {
                unset($accounts2[$account]);
            }
        }
        arsort($accounts2);
        $ranked2 = array();
        $count = 0;
        foreach ($accounts2 as $account => $follower_count) {
            $count++;
            $ranked2[$account] = array(
                'rank' => $count,
                'follower_count' => $follower_count
                );
        }

        $output = array();

        $count = 0;
        foreach ($accounts1 as $account => $follower_count) {
            $count++;
            if ($count > $this->number_of_top_accounts_to_show) {
                break;
            }
            $previous_rank = isset($ranked2[$account]) ? $ranked2[$account]['rank'] : '0';
            $previous_follower_count = isset($ranked2[$account]) ? $ranked2[$account]['follower_count'] : '0';
            $change_in_rank = ($previous_rank) ? ($previous_rank - $count) : '**NEW**';
            $change_in_follower_count = ($previous_follower_count) ? ($follower_count - $previous_follower_count) : '**NEW**';
            $change_in_follower_count_percentage = '';
            if ($previous_follower_count) {
                $change_in_follower_count_percentage = number_format(($change_in_follower_count / $follower_count) * 100, 0);
            }
            $output[$account] = array(
                'rank' => $count,
                'follower_count' => $follower_count,
                'change_in_rank' => $change_in_rank,
                'change_in_follower_count' => $change_in_follower_count,
                'change_in_follower_count_percentage' => $change_in_follower_count_percentage,
                );

            //var_dump($output[$account]);

        }


        //$rising_stars = $output;
        //$rising_stars = $this->sortBy('change_in_rank', $output, 'desc');
        //var_dump($output);
//die();
/*

|  46 |+1627 |             @0 |464 |+451 |97%|
|  50 |+82 |             @1 |458 |+305 |67%|
|  98 |+63 |             @2 |264 |+131 |50%|
|  15 |+42 |             @3 |641 |+332 |52%|
|  77 |+38 |             @4 |314 |+142 |45%|
|  96 |+38 |             @5 |267 |+114 |43%|
|  72 |+27 |             @6 |345 |+158 |46%|
|  61 |+18 |             @7 |410 |+177 |43%|
|  22 |+15 |             @8 |596 |+184 |31%|
|  28 |+14 |             @9 |572 |+189 |33%|
|  11 |+12 |            @10 |723 |+246 |34%|
|  9 |+11 |            @11 |822 |+337 |41%|
|  80 |+10 |            @12 |307 |+101 |33%|
|  82 |+9 |            @13 |305 |+100 |33%|
|  18 |+9 |            @14 |628 |+162 |26%|

|  44 | |            @31 |485 | ||
|  68 | |            @32 |380 | ||
|  89 | |            @38 |292 | ||
|  100 | |            @40 |263 | ||
|  56 | |            @42 |437 | ||

|  67 | |            @35 |380 | ||
|  88 | |            @37 |292 | ||
|  55 | |            @42 |437 | ||


*/



        $header = "|  Rank  | Change |   Account|    Number of Followers   |  Change |  | \n";
        $header .= "|:--:|:--:|:----------------:|:------------------------:|:--:|:--:|\n";

        print "\n## <center>TOP $this->number_of_top_accounts_to_show USERS BY FOLLOWER COUNT </center>\n\n";
        print $header;
        foreach ($output as $account => $details) {
            if ($details['rank'] > $this->number_of_top_accounts_to_show) {
                break;
            }
            print '|  ' . $details['rank'];
            print ' |';
            if ($details['change_in_rank'] > 0) {
                print '+' . $details['change_in_rank'];
            } elseif ($details['change_in_rank'] < 0) {
                print $details['change_in_rank'];
            }
            print ' |' . sprintf('%15s','@'.$account);
            print ' |' . $details['follower_count'];
            print ' |';
            if ($details['change_in_follower_count'] > 0) {
                print '+' . $details['change_in_follower_count'];
            } elseif ($details['change_in_follower_count'] < 0) {
                print $details['change_in_follower_count'];
            }
            print ' |';
            if ($details['change_in_follower_count_percentage'] != 0) {
               print $details['change_in_follower_count_percentage'] . '%';
            }
            print "|\n";
        }

        print "# Stats\n";

        print "| | Count | Change | % Change |\n";
        print "|:-----:|:-----:|:-----:|:----------------:|\n";
        print "| Total number of accounts: |  64,106 | +8,290 | +14.8%|\n";
        print "|Accounts with at least one post:  |  21,311 | +1,916  |  +9.8%\n";
        print "|Accounts with at least **ten** followers: |   1,690 |   N/A  |    |\n";

    }

    public function printTopFollowed() {
        print "printTopFollowed...\n";
        $accounts = array();
        $file = @fopen("saveFollowerCounts_data.txt","r");
        if (!$file) {
            $this->saveStartCountToFile('saveFollowerCounts', 0);
            $this->saveFollowerCounts();
            $file = @fopen("saveFollowerCounts_data.txt","r");
        }
        $count = 0;
        while(!feof($file)) {
            $count++;
            $line = fgetcsv($file);
            $accounts[trim($line[0])] = trim($line[1]);
        }
        fclose($file);

        $total = $this->getStartCountFromFile('saveFollowerCounts');
        if ($count < $total) {
            $this->saveFollowerCounts();
            $file = @fopen("saveFollowerCounts_data.txt","r");
            while(!feof($file)) {
                $line = fgetcsv($file);
                $accounts[trim($line[0])] = trim($line[1]);
            }
        }
        arsort($accounts);

        $header = "|    |           Account|    Number of Followers   | \n";
        $header .= "|:--:|:----------------:|:------------------------:|\n";

        print "\n## <center>TOP $this->number_of_top_accounts_to_show USERS BY FOLLOWER COUNT </center>\n\n";
        print $header;
        $count = 0;
        foreach ($accounts as $account => $follower_count) {
            $count++;
            if ($count > $this->number_of_top_accounts_to_show) {
                break;
            }
            print '|  ' . $count . '  |' . sprintf('%15s','@'.$account) . ': |   ' . $follower_count . "   |\n";
        }
    }

    public function getStartCountFromFile($method)
    {
        $start = @file_get_contents($method . '_start.txt');
        if (!$start) {
            $start = 0;
            $this->saveStartCountToFile($method, $start);
        }
        return $start;
    }

    public function saveStartCountToFile($method, $start)
    {
        file_put_contents($method . '_start.txt',$start);
    }

    public function sortBy($field, &$array, $direction = 'asc')
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

        return true;
    }


}
