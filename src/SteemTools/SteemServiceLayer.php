<?php
namespace SteemTools;

class SteemServiceLayer
{
    private $debug = false;
    //private $webservice_url = 'https://node.steem.ws';
    //private $webservice_url = 'https://steemd.steemit.com';
    //private $webservice_url = 'https://steemd.pevo.science';
    private $webservice_url = 'https://api.steemit.com';
    private $throw_exception = false;

    public function __construct($config = array())
    {
        if (array_key_exists('debug', $config)) {
            $this->debug = $config['debug'];
        }
        if (array_key_exists('webservice_url', $config)) {
            $this->webservice_url = $config['webservice_url'];
        }
        if (array_key_exists('throw_exception', $config)) {
            $this->throw_exception = $config['throw_exception'];
        }
    }

    public function call($method, $params = array()) {
        $request = $this->getRequest($method, $params);
        $response = $this->curl($request);
        if (is_null($response) || array_key_exists('error', $response)) {
            if ($this->throw_exception) {
                if (is_null($response)) {
                    throw new Exception($method);
                } else {
                    throw new Exception($response['error']);
                }
            } else {
                if (is_null($response)) {
                    var_dump($method);
                    var_dump($params);
                } else {
                    var_dump($response['error']);
                }
                die();
            }
        }
        return $response['result'];
    }

    public function getRequest($method, $params) {
        $request = array(
            "jsonrpc" => "2.0",
            "method" => $method,
            "params" => $params,
            "id" => 0
            );
        $request_json = json_encode($request);

        if ($this->debug) {
            print $request_json . "\n";
        }

        return $request_json;
    }

    public function curl($data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->webservice_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        if ($this->debug) {
            print $result . "\n";
        }

        $result = json_decode($result, true);

        return $result;
    }

}