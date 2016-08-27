<?php
namespace SteemTools\Reporting;

setlocale(LC_MONETARY, 'en_US');
//date_default_timezone_set('America/Chicago');
date_default_timezone_set('UTC');


class ExchangeTransfers
{
    public $SteemAPI;
    public $number_of_top_accounts_to_show = 50;
    public $exchange_accounts = array('poloniex', 'bittrex', 'blocktrades', 'openledger');
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
            $results = $this->getAccountTransferHistory($exchange_account, $start, $end);
            $transfers = $this->getTransfers($results);
            $this->exchange_data[$exchange_account] = $transfers;
        }
        return $this->exchange_data;
    }

    public function getTransfers($result)
    {
        $transfers = array();
        foreach($result as $block) {
            $transfer = $block[1]['op'][1];
            list($amount, $currency) = sscanf($transfer['amount'], "%f %s");
            $transfer['amount'] = $amount;
            $transfer['currency'] = $currency;
            $transfers[] = $transfer;
        }
        return $transfers;
    }

    public function runDailyReport($start, $end)
    {
        // print the header
        $header = "| Date | ";
        foreach($this->exchange_accounts as $exchange_account) {
            $header .= $exchange_account . " | ";
        }
        $header .= " Total |\n";
        $header .= "|:----:|:";
        foreach($this->exchange_accounts as $exchange_account) {
            $header .= "-------:|:";
        }
        $header .= "----:|\n";

        $sbd_report = $header;
        $steem_report = $header;
        $usd_report = $header;

        $startDate = new \DateTime($start);
        $endDate = new \DateTime($end);
        $days = $startDate->diff($endDate)->format("%a");

        $sbd_price = $this->SteemAPI->getCurrentMedianHistoryPrice('SBD');
        $steem_price = $this->SteemAPI->getCurrentMedianHistoryPrice('STEEM');
        $prices = array('SBD' => $sbd_price, 'STEEM' => $steem_price);

        for ($day = 0; $day < $days; $day++) {
            $currentDate = clone $startDate;
            $nextDate = clone $startDate;
            $currentDate->add(new \DateInterval('P' . $day . 'D'));
            $nextDate->add(new \DateInterval('P' . ($day+1) . 'D'));
            $this->getExchangeData($currentDate->format('Y-m-d'), $nextDate->format('Y-m-d'));
            $balances = $this->getBalances();
            $usd_balances = array();
            foreach ($balances as $currency => $account_balances) {
                foreach ($account_balances as $account => $amount) {
                    if (!array_key_exists($account, $usd_balances)) {
                        $usd_balances[$account] = 0;
                    }
                    $usd_balances[$account] += $prices[$currency] * $amount;
                }
            }
            //print "First included transfer: " . date('c', $this->min_timestamp) . "\n";
            //print "Last included transfer: " . date('c', $this->max_timestamp) . "\n";
            $sbd_report .= "| " . $currentDate->format('Y-m-d') . " | ";
            $steem_report .= "| " . $currentDate->format('Y-m-d') . " | ";
            $usd_report .= "| " . $currentDate->format('Y-m-d') . " | ";

            foreach($this->exchange_accounts as $exchange_account) {
                $sbd_report .= number_format($balances['SBD'][$exchange_account],0) . " | ";
                $steem_report .= number_format($balances['STEEM'][$exchange_account],0) . " | ";
                $usd_report .= money_format('%(#6.0n',$usd_balances[$exchange_account]) . " | ";
                unset($balances['SBD'][$exchange_account]);
                unset($balances['STEEM'][$exchange_account]);
                unset($usd_balances[$exchange_account]);
            }
            $sbd_report .= number_format(0-array_sum($balances['SBD']), 0) . " |\n";
            $steem_report .= number_format(0-array_sum($balances['STEEM']), 0) . " |\n";
            $usd_report .= money_format('%(#6.0n',0-array_sum($usd_balances)) . " |\n";

            $this->min_timestamp = time();
            $this->max_timestamp = 0;
        }

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
        // Do some reporting for our exchange accounts
        foreach($this->exchange_accounts as $exchange_account) {
            print '@' . $exchange_account . ' STEEM transfer total: ' . number_format($balances['STEEM'][$exchange_account],3) . "\n";
            print '@' . $exchange_account . ' SBD transfer total: ' . number_format($balances['SBD'][$exchange_account],3) . "\n";
            unset($balances['SBD'][$exchange_account]);
            unset($balances['STEEM'][$exchange_account]);
        }

        $sbd_price = $this->SteemAPI->getCurrentMedianHistoryPrice('SBD');
        $steem_price = $this->SteemAPI->getCurrentMedianHistoryPrice('STEEM');

        $prices = array('SBD' => $sbd_price, 'STEEM' => $steem_price);

        // Get USD amounts using our conversion rates for SBD / STEEM
        $usd_balances = array();
        foreach ($balances as $currency => $account_balances) {
            foreach ($account_balances as $account => $amount) {
                if (!array_key_exists($account, $usd_balances)) {
                    $usd_balances[$account] = 0;
                }
                $usd_balances[$account] += $prices[$currency] * $amount;
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

        $withdraws = array_filter($usd_balances, function ($v) {
          return $v < 0;
        });
        $deposits = array_filter($usd_balances, function ($v) {
          return $v > 0;
        });
        arsort($deposits);

        print "\n" . str_pad(" Withdrawl to Deposit Ratio ",$report_width,'-',STR_PAD_BOTH) . "\n\n";

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


        print "\n## <center>TOP $this->number_of_top_accounts_to_show WITHDRAWALS</center>\n\n";
        print $header;
        $count = 0;
        foreach ($top_withdraws as $account => $amount) {
            $count++;
            print '|' . $count . '|' . sprintf('%20s','@'.$account) . ': | ' . money_format('%(#8n',$amount) . "|\n";
        }
        print "\n## <center>TOP $this->number_of_top_accounts_to_show DEPOSITS </center>\n\n";
        //print "\n## " . str_pad(" TOP $this->number_of_top_accounts_to_show DEPOSITS (powering up?) ",80,'-',STR_PAD_BOTH) . "\n\n";
        print $header;
        $count = 0;
        foreach ($top_deposits as $account => $amount) {
            $count++;
            print '|' . $count . '|' . sprintf('%20s','@'.$account) . ': | ' . money_format('%(#8n',$amount) . "|\n";
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
        $filtered_results = $this->filterAccountHistory($result,$start_timestamp,$end_timestamp,array('transfer'));

        while ($start_timestamp < $info['min_timestamp']) {
            $from = $info['min_id'];
            if ($limit > $from) {
                $limit = $from;
            }
            $params = array($account, $info['min_id'], $limit);
            $result = $this->SteemAPI->getAccountHistory($params);
            $filtered_results = array_merge(
                $filtered_results,
                $this->filterAccountHistory($result,$start_timestamp,$end_timestamp,array('transfer'))
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