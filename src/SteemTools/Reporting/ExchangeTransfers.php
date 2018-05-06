<?php
namespace SteemTools\Reporting;

setlocale(LC_MONETARY, 'en_US');
//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');


class ExchangeTransfers
{
    public $SteemAPI;
    public $number_of_top_accounts_to_show = 50;
    public $exchange_accounts = array('poloniex', 'bittrex', 'blocktrades', 'openledger', 'hitbtc', 'changelly','freewallet.org','coinpayments.net','rudex','binance','steemexchanger','upbit','gopax','huobi');
    public $linked_exchange_accounts = array('hitbtc' => array('hitbtc-exchange','hitbtc-payout'), 'freewallet.org' => array('freewallet.org','freewallet'), 'openledger' => array('openledger','openledger-dex'), 'binance' => array('binance-hot','deepcrypto8'), 'upbit' => array('upbit-exchange','myupbit'), 'gopax' => array('gopax','gopax-deposit'), 'huobi' => array('huobi-pro','huobi-withdrawal'));
    public $exchange_data = array();
    public $min_timestamp = 0;
    public $max_timestamp = 0;

    public function __construct($SteemAPI, $config = array())
    {
        $this->SteemAPI = $SteemAPI;
        if (array_key_exists('number_of_top_accounts_to_show', $config)) {
            $this->number_of_top_accounts_to_show = $config['number_of_top_accounts_to_show'];
        }
        if (array_key_exists('exchange_accounts', $config)) {
            $this->exchange_accounts = $config['exchange_accounts'];
        }
        $this->min_timestamp = time();
    }

    public function getExchangeData($start, $end)
    {
        foreach($this->exchange_accounts as $exchange_account) {
            if (!array_key_exists($exchange_account, $this->linked_exchange_accounts)) {
                $this->getExchangeDataForAccount($exchange_account, $exchange_account, $start, $end);
            } else {
                foreach ($this->linked_exchange_accounts[$exchange_account] as $actual_account) {
                    $this->getExchangeDataForAccount($actual_account, $exchange_account, $start, $end);
                }
            }

        }
        return $this->exchange_data;
    }

    public function getExchangeDataForAccount($exchange_account, $account_alias, $start, $end)
    {
        $results = $this->getAccountTransferHistory($exchange_account, $start, $end);
        $transfers = $this->getTransfers($results);
        $this->exchange_data[$account_alias] = $transfers;
    }

    public function getTransfers($result)
    {
        $transfers = array();
        foreach($result as $block) {
            $transfer = $block[1]['op'][1];
            list($amount, $currency) = sscanf($transfer['amount'], "%f %s");
            $transfer['amount'] = $amount;
            $transfer['currency'] = $currency;
            $is_to_an_exchange = false;
            foreach ($this->linked_exchange_accounts as $alias => $accounts) {
                foreach ($accounts as $account) {
                    if ($account == $transfer['from']) {
                        $transfer['from'] = $alias;
                        $is_to_an_exchange = true;
                    }
                    if ($account == $transfer['to']) {
                        $transfer['to'] = $alias;
                        $is_to_an_exchange = true;
                    }
                }
            }

/*
if ($transfer['to'] == "" || $transfer['from'] == "") {
    var_dump($transfer);
}
*/
/*
if ($transfer['to'] == "huobi" || $transfer['from'] == "huobi") {
    var_dump($transfer);
}
*/


            $transfers[] = $transfer;

        }
        return $transfers;
    }

    public function runHourlyReport($start, $end)
    {
        // print the header
        $header = "Date,";
        foreach($this->exchange_accounts as $exchange_account) {
            $header .= $exchange_account . ",";
        }
        $header .= "Total\n";

        $sbd_report = $header;
        $steem_report = $header;
        $usd_report = $header;

        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $days = $startDate->diff($endDate)->format("%a");
        $price_history = $this->SteemAPI->getPriceHistoryInUSD($start, $end);

        for ($day = 0; $day < $days; $day++) {
            for ($hour = 0; $hour < 24; $hour++) {
                $currentDate = clone $startDate;
                $nextDate = clone $startDate;
                $currentDate->add(new \DateInterval('P' . $day . 'DT' . $hour . 'H'));
                $nextDate->add(new \DateInterval('P' . ($day+1) . 'DT' . ($hour+1) . 'H'));

                print "Processing " . $currentDate->format('Y-m-d H:i') . "\n";

                $this->exchange_data = array();
                $this->getExchangeData($currentDate->format('Y-m-d H:i'), $nextDate->format('Y-m-d H:i'));
                $balances = $this->getBalances();
                $usd_balances = array();
                foreach ($balances as $currency => $account_balances) {
                    foreach ($account_balances as $account => $amount) {
                        if (!array_key_exists($account, $usd_balances)) {
                            $usd_balances[$account] = 0;
                        }
                        $usd_balances[$account] += $price_history[$currentDate->format('Y-m-d')][$currency] * $amount;
                    }
                }
                //print "First included transfer: " . date('c', $this->min_timestamp) . "\n";
                //print "Last included transfer: " . date('c', $this->max_timestamp) . "\n";
                $sbd_report .= $currentDate->format('Y-m-d H:i') . ",";
                $steem_report .= $currentDate->format('Y-m-d H:i') . ",";
                $usd_report .= $currentDate->format('Y-m-d H:i') . ",";

                foreach($this->exchange_accounts as $exchange_account) {
                    $sbd_report .= number_format($balances['SBD'][$exchange_account],0,'.','') . ",";
                    $steem_report .= number_format($balances['STEEM'][$exchange_account],0,'.','') . ",";
                    $usd_report .= number_format($usd_balances[$exchange_account],0,'.','') . ",";
                    unset($balances['SBD'][$exchange_account]);
                    unset($balances['STEEM'][$exchange_account]);
                    unset($usd_balances[$exchange_account]);
                }
                $sbd_report .= number_format(0-array_sum($balances['SBD']), 0,'.','') . "\n";
                $steem_report .= number_format(0-array_sum($balances['STEEM']), 0,'.','') . "\n";
                $usd_report .= number_format(0-array_sum($usd_balances), 0,'.','') . "\n";

                $this->min_timestamp = time();
                $this->max_timestamp = 0;
            }

            file_put_contents('STEEM_' . $endDate->format('Y-m-d')  . '.txt', $steem_report);
            file_put_contents('SBD_' . $endDate->format('Y-m-d')  . '.txt', $sbd_report);
            file_put_contents('USD_' . $endDate->format('Y-m-d')  . '.txt', $usd_report);

            print "## STEEM\n";
            print $steem_report;
            print "\n\n\n";

            print "## SBD\n";
            print $sbd_report;
            print "\n\n\n";

            print "## USD\n";
            print $usd_report;
            print "\n\n\n";

        }
    }

    public function runReport($start, $end)
    {
        $report_width = 70;
        $end_display = strtotime($end);
        $end_display = date('m/d/Y',strtotime('-1 day', $end_display));

        print "# Exchange Transfer Activity from " . date('m/d/Y',strtotime($start)) . " to " . $end_display . "\n";

        $this->getExchangeData($start, $end);
        $balances = $this->getBalances();

        print "First included transfer: " . date('c', $this->min_timestamp) . "\n";
        print "Last included transfer: " . date('c', $this->max_timestamp) . "\n";

        print "```\n";
        print "\n" . str_pad(" Exchanges ",$report_width,'-',STR_PAD_BOTH) . "\n\n";
        $price_history = $this->SteemAPI->getPriceHistoryInUSD($start, $end);
        // Do some reporting for our exchange accounts
        $exchanges_sbd = array();
        $exchanges_steem = array();
        $exchanges_total_usd = array();
        foreach($this->exchange_accounts as $exchange_account) {
            $exchanges_sbd[$exchange_account] = $balances['SBD'][$exchange_account];
            $exchanges_steem[$exchange_account] = $balances['STEEM'][$exchange_account];
            $exchanges_total_usd[$exchange_account] = ($exchanges_sbd[$exchange_account] * $price_history['average']['SBD']) + ($exchanges_steem[$exchange_account] * $price_history['average']['STEEM']);
            unset($balances['SBD'][$exchange_account]);
            unset($balances['STEEM'][$exchange_account]);
        }
        arsort($exchanges_total_usd);
        foreach($exchanges_total_usd as $exchange_account => $usd_total) {
            print '@' . $exchange_account . ' STEEM transfer total: ' . number_format($exchanges_steem[$exchange_account],3) . "\n";
            print '@' . $exchange_account . ' SBD transfer total: ' . number_format($exchanges_sbd[$exchange_account],3) . "\n";
        }

        print "\n\nPrice Averages: \n";
        print '- STEEM: $' . round($price_history['average']['STEEM'],5) . "\n";
        print '- SBD: $' . round($price_history['average']['SBD'],5) . "\n";
        print "\n\n";

        //var_dump($price_history);

        // Get USD amounts using our conversion rates for SBD / STEEM
        $usd_balances = array();
        foreach ($balances as $currency => $account_balances) {
            foreach ($account_balances as $account => $amount) {
                if (!array_key_exists($account, $usd_balances)) {
                    $usd_balances[$account] = 0;
                }
                $usd_balances[$account] += $price_history['average'][$currency] * $amount;
            }
        }
        asort($usd_balances);

        print "\n" . str_pad(" Total Transferred ",$report_width,'-',STR_PAD_BOTH) . "\n\n";
        print "Total STEEM transferred: " . number_format(array_sum($balances['STEEM']), 3) . "\n";
        print "Total SBD transferred: " . number_format(array_sum($balances['SBD']), 3) . "\n";
        print "Total USD transferred: " . money_format('%(#8n',array_sum($usd_balances)) . "\n";


        // Exclude steemit account
        if (array_key_exists('steemit',$usd_balances)) {
            $steem_balances_without_steemit = $balances['STEEM'];
            $sbd_balances_without_steemit = $balances['SBD'];
            $usd_balances_without_steemit = $usd_balances;
            unset($steem_balances_without_steemit['steemit']);
            unset($sbd_balances_without_steemit['steemit']);
            unset($usd_balances_without_steemit['steemit']);

            print "\n" . str_pad(" Total Transferred, Excluding Steemit Account ",$report_width,'-',STR_PAD_BOTH) . "\n\n";
            print "Total STEEM transferred (excluding steemit): " . number_format(array_sum($steem_balances_without_steemit), 3) . "\n";
            print "Total SBD transferred (excluding steemit): " . number_format(array_sum($sbd_balances_without_steemit), 3) . "\n";
            print "Total USD transferred (excluding steemit): " . money_format('%(#8n',array_sum($usd_balances_without_steemit)) . "\n";
        }

        if (array_key_exists('steemit2',$usd_balances)) {
            $steem_balances_without_steemit = $balances['STEEM'];
            $sbd_balances_without_steemit = $balances['SBD'];
            $usd_balances_without_steemit = $usd_balances;
            unset($steem_balances_without_steemit['steemit2']);
            unset($sbd_balances_without_steemit['steemit2']);
            unset($usd_balances_without_steemit['steemit2']);

            print "\n" . str_pad(" Total Transferred, Excluding Steemit2 Account ",$report_width,'-',STR_PAD_BOTH) . "\n\n";
            print "Total STEEM transferred (excluding steemit2): " . number_format(array_sum($steem_balances_without_steemit), 3) . "\n";
            print "Total SBD transferred (excluding steemit2): " . number_format(array_sum($sbd_balances_without_steemit), 3) . "\n";
            print "Total USD transferred (excluding steemit2): " . money_format('%(#8n',array_sum($usd_balances_without_steemit)) . "\n";
        }

        $withdraws = array_filter($usd_balances, function ($v) {
          return $v < 0;
        });
        $deposits = array_filter($usd_balances, function ($v) {
          return $v > 0;
        });
        arsort($deposits);

        print "\n" . str_pad(" Withdrawal to Deposit Ratio ",$report_width,'-',STR_PAD_BOTH) . "\n\n";

        print "Accounts withdrawing: " . count($withdraws) . "\n";
        print "Average withdrawal amount: " . money_format('%(#8n',array_sum($withdraws) / count($withdraws)) . "\n";
        print "Median withdrawal amount: " . money_format('%(#8n',$this->array_median($withdraws)) . "\n";

        print "\n";

        print "Accounts depositing: " . count($deposits) . "\n";
        print "Average deposit amount: " . money_format('%(#8n',array_sum($deposits) / count($withdraws)) . "\n";
        print "Median deposit amount: " . money_format('%(#8n',$this->array_median($deposits)) . "\n";

        print "\n";

        print "Ratio of withdrawals to deposits: " . number_format(count($withdraws)/count($deposits),2) . "/1\n";

        print "```\n";


        // get top accounts
        $top_withdraws = array_slice($withdraws, 0, $this->number_of_top_accounts_to_show);
        $top_deposits = array_slice($deposits, 0, $this->number_of_top_accounts_to_show);

        $header = "|    |           Account|    Net Transfer Amount   | \n";
        $header .= "|:--:|:----------------:|:------------------------:|\n";


        print "\n## <center>TOP $this->number_of_top_accounts_to_show WITHDRAWALS THIS WEEK</center>\n\n";
        print $header;
        $count = 0;
        foreach ($top_withdraws as $account => $amount) {
            $count++;
            print '|' . $count . '|' . sprintf('%20s',$account) . ': | ' . money_format('%(#8n',$amount) . "|\n";
        }
        print "\n## <center>TOP $this->number_of_top_accounts_to_show DEPOSITS THIS WEEK</center>\n\n";
        //print "\n## " . str_pad(" TOP $this->number_of_top_accounts_to_show DEPOSITS (powering up?) ",80,'-',STR_PAD_BOTH) . "\n\n";
        print $header;
        $count = 0;
        foreach ($top_deposits as $account => $amount) {
            $count++;
            print '|' . $count . '|' . sprintf('%20s',$account) . ': | ' . money_format('%(#8n',$amount) . "|\n";
        }
    }


    public function getBalances()
    {
        // Calculate transfer balances for SBD and STEEM for each account
        $sbd_balances = array();
        $steem_balances = array();
        foreach($this->exchange_accounts as $exchange_account) {
            foreach ($this->exchange_data[$exchange_account] as $transfer) {

                if ($transfer['currency'] == 'SBD') {
                    if (!array_key_exists($transfer['from'], $sbd_balances)) {
                        $sbd_balances[$transfer['from']] = 0;
                    }
                    if (!array_key_exists($transfer['to'], $sbd_balances)) {
                        $sbd_balances[$transfer['to']] = 0;
                    }
                    $sbd_balances[$transfer['to']] += $transfer['amount'];
                    $sbd_balances[$transfer['from']] -= $transfer['amount'];
                }

                if ($transfer['currency'] == 'STEEM') {
                    if (!array_key_exists($transfer['from'], $steem_balances)) {
                        $steem_balances[$transfer['from']] = 0;
                    }
                    if (!array_key_exists($transfer['to'], $steem_balances)) {
                        $steem_balances[$transfer['to']] = 0;
                    }
                    $steem_balances[$transfer['to']] += $transfer['amount'];
                    $steem_balances[$transfer['from']] -= $transfer['amount'];
                }

            }
        }
        asort($sbd_balances);
        asort($steem_balances);

//var_dump($steem_balances);


        return array(
            'SBD' => $sbd_balances,
            'STEEM' => $steem_balances
            );
    }


    public function getResultInfo($blocks)
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

    public function getAccountTransferHistory($account, $start, $end)
    {
        $start_timestamp = strtotime($start);
        $end_timestamp = strtotime($end);
        $limit = 2000;
        $params = array($account, -1, $limit);
        $result = $this->SteemAPI->getAccountHistory($params);
        $info = $this->getResultInfo($result);
        $filtered_results = $this->filterAccountHistory($result,$start_timestamp,$end_timestamp,array('transfer','transfer_to_vesting'));

        while ($start_timestamp < $info['min_timestamp']) {
            $from = $info['min_id'];
            if ($limit > $from) {
                $limit = $from;
            }
            if ($limit == $from && $from == 0) {
                break;
            }
            $params = array($account, $info['min_id'], $limit);
            $result = $this->SteemAPI->getAccountHistory($params);
            $filtered_results = array_merge(
                $filtered_results,
                $this->filterAccountHistory($result,$start_timestamp,$end_timestamp,array('transfer','transfer_to_vesting'))
                );
            $info = $this->getResultInfo($result);
        }
        return $filtered_results;
    }

    public function filterAccountHistory($result, $start_timestamp, $end_timestamp, $ops = array())
    {
        $filtered_results = array();
        if (count($result)) {
            foreach($result as $block) {
                $timestamp = strtotime($block[1]['timestamp']);
                if (in_array($block[1]['op'][0], $ops)
                    && $timestamp >= $start_timestamp
                    && $timestamp <= $end_timestamp
                    ) {
                    $this->min_timestamp = min($this->min_timestamp,$timestamp);
                    $this->max_timestamp = max($this->max_timestamp,$timestamp);
                    $filtered_results[] = $block;
                }
            }
        }
        return $filtered_results;
    }


    /**
    * Helper Function
    **/
    public function array_median($arr) {
        $count = count($arr); //total numbers in array
        $middleval = floor(($count-1)/2); // find the middle value, or the lowest middle value
        if($count % 2) { // odd number, middle is the median
            $median = array_slice($arr, $middleval, 1);
            $median = array_pop($median);
        } else { // even number, calculate avg of 2 medians
            $low = array_slice($arr, $middleval, 1);
            $low = array_pop($low);
            $high = array_slice($arr, $middleval+1, 1);
            $high = array_pop($high);
            $median = (($low+$high)/2);
        }
        return $median;
    }

}