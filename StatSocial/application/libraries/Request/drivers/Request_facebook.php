<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

class CI_Request_facebook extends Request_driver {

    private $url                = 'https://graph.facebook.com/search';
    private $app_id             = '458284534272920';
    private $app_secret         = '88bb0b80af25a303c7ab0b9bc50ee00e';
    
    public function get(array $terms)
    {                  
        // &fields=from.id,message,type,created_time <- filter...
        
        return json_decode($this->http($this->url.'?'.$this->http_build_query($terms).'&access_token='.$this->app_id.'|'.$this->app_secret));    
    }    
}