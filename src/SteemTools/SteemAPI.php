<?php
namespace SteemTools;

class SteemAPI
{
    private $SteemServiceLayer;

    public function __construct($SteemServiceLayer)
    {
        $this->SteemServiceLayer = $SteemServiceLayer;
    }

    // customized
    public function getCurrentMedianHistoryPrice($currency = 'STEEM')
    {
        // TEMP: until I figure this out...
        if ($currency == 'STEEM') {
            return 1.49;
        }
        if ($currency == 'SBD') {
            return 0.86;
        }

        $result = $this->SteemServiceLayer->call('get_current_median_history_price');
        $price = 0;
        if ($currency == 'STEEM') {
            $price = $result['base'] * $result['quote'];
        }
        if ($currency == 'SBD') {
            $price = $result['base'] * $result['quote'];
        }
        return $price;
    }

    public function getAccountHistory($params)
    {
        $result = $this->SteemServiceLayer->call('get_account_history', $params);
        return $result;
    }

}