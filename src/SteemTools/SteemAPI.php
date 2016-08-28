<?php
namespace SteemTools;

class SteemAPI
{
    private $SteemServiceLayer;
    private $api_ids = array();

    public function __construct($SteemServiceLayer)
    {
        $this->SteemServiceLayer = $SteemServiceLayer;
    }

    // customized
    public function getCurrentMedianHistoryPrice($currency = 'STEEM')
    {
        // TEMP: until I figure this out...
        if ($currency == 'STEEM') {
            return 0.80;
        }
        if ($currency == 'SBD') {
            return 0.92;
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

    public function getContent($params)
    {
        $result = $this->SteemServiceLayer->call('get_content', $params);
        return $result;
    }

    public function getAccountVotes($params)
    {
        $result = $this->SteemServiceLayer->call('get_account_votes', $params);
        return $result;
    }

    public function getAccountHistory($params)
    {
        $result = $this->SteemServiceLayer->call('get_account_history', $params);
        return $result;
    }

    public function getFollowerCount($account)
    {
        $followers = $this->getFollowers($account);
        return count($followers);
    }

    public function getFollowers($account, $start = '')
    {
        $limit = 100;
        $followers = array();
        $params = array($this->getFollowAPIID(),'get_followers',array($account,$start,'blog',$limit));
        $followers = $this->SteemServiceLayer->call('call', $params);
        if (count($followers) == $limit) {
            $last_account = $followers[$limit-1];
            $more_followers = $this->getFollowers($account, $last_account['follower']);
            array_pop($followers);
            $followers = array_merge($followers, $more_followers);
        }
        return $followers;
    }

    public function lookupAccounts($params)
    {
        $accounts = $this->SteemServiceLayer->call('lookup_accounts', $params);
        return $accounts;
    }

    public function getAccounts($accounts)
    {
        $get_accounts_results = $this->SteemServiceLayer->call('get_accounts', array($accounts));
        return $get_accounts_results;
    }

    public function getFollowAPIID()
    {
        return $this->getAPIID('follow_api');
    }

    public function getAPIID($api_name)
    {
        if (array_key_exists($api_name, $this->api_ids)) {
            return $this->api_ids[$api_name];
        }
        $response = $this->SteemServiceLayer->call('call', array(1,'get_api_by_name',array($api_name)));
        $this->api_ids[$api_name] = $response;
        return $response;
    }

    public function cachedCall($call, $params, $serialize = false, $batch = false, $batch_size = 100)
    {
        $data = @file_get_contents($call . '_data.txt');
        if ($data) {
            if ($serialize) {
                $data = unserialize($data);
            }
            return $data;
        }
        $data = $this->{$call}($params);

        if ($serialize) {
            $data_for_file = serialize($data);
        } else {
            $data_for_file = $data;
        }
        file_put_contents($call . '_data.txt',$data_for_file);
        return $data;
/*
        $start = @file_get_contents($call . '_start.txt');
        if (!$start) {
            $start = 0;
            file_put_contents($call . '_start.txt',$start);
        }
*/
    }

}