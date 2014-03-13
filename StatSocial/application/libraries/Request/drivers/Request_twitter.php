<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

class CI_Request_twitter extends Request_driver {

    private $oauth;
    private $url                = 'https://api.twitter.com/1.1/search/tweets.json';
    private $consumer_key       = '';
    private $cunsumer_secret    = '';
    private $access_token       = '';
    private $access_secret      = '';
    
    public function get(array $terms)
    {    
        $oauth = array_map('rawurlencode', $terms);
        
        $oauth['oauth_consumer_key']    = $this->consumer_key;
        $oauth['oauth_nonce']           = time();
        $oauth['oauth_signature_method']= 'HMAC-SHA1';
        $oauth['oauth_token']           = $this->access_token;
        $oauth['oauth_timestamp']       = time();
        $oauth['oauth_version']         = '1.0';  
        $oauth['oauth_signature']       = base64_encode(hash_hmac('sha1', $this->build_base_string($this->url, 'GET', $oauth), rawurlencode($this->cunsumer_secret).'&'.rawurlencode($this->access_secret), TRUE));
        
        $url = $this->url .= '?'.$this->http_build_query($terms); 
        
        return json_decode($this->http($url, array('Authorization: OAuth '.$this->http_build_query($oauth, ', ', TRUE))));
    }
    
    private function build_base_string($url, $method, $params)
    {
        $return = array();
        ksort($params);
        
        foreach ($params as $key => $val)
        {
            $return[] = $key.'='.$val;
        }
        
        return $method.'&'.rawurlencode($url).'&'.rawurlencode(implode('&', $return));
    }
}