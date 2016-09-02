<?php
namespace SteemTools\Library;

// from https://github.com/aaroncox/steemdb/blob/master/app/libs/steemd.php

use JsonRPC\Client;
use JsonRPC\HttpClient;

class steemd
{

  protected $host;
  protected $api_ids = array();
  protected $client;

  public function __construct($host)
  {
    $this->host = $host;
    $httpClient = new HttpClient($host);
    $httpClient->withoutSslVerification();
    $this->client = new Client($host, false, $httpClient);
  }

  public function getAccountHistory($username, $limit = 100, $skip = -1)
  {
    $api = $this->getApi('database_api');
    return $this->client->call($api, 'get_account_history', [$username, $skip, $limit]);
  }

  public function getAccountVotes($username)
  {
    $api = $this->getApi('database_api');
    return $this->client->call($api, 'get_account_votes', [$username]);
  }

  public function getContent($author, $permlink)
  {
    $api = $this->getApi('database_api');
    return $this->client->call($api, 'get_content', [$author, $permlink]);
  }

  public function getProps()
  {
    $api = $this->getApi('database_api');
    return $this->client->call($api, 'get_dynamic_global_properties', []);
  }

  public function getApi($name)
  {
    if (!array_key_exists($name, $this->api_ids)) {
      $key = $this->client->call(1, 'get_api_by_name', [$name]);
      $this->api_ids[$name] = $key;
    }
    return $this->api_ids[$name];
  }

  public function getFollowing($username, $limit = 100, $skip = -1)
  {
    $api = $this->getApi('follow_api');
    return $this->client->call($api, 'get_following', [$username, $skip, $limit]);;
  }
}