<?php defined("BASEPATH") OR exit("No direct script access allowed."); 

class CI_Request_ndw extends Request_driver {
    
    private $url                = 'ftp://83.247.110.3/gebeurtenisinfo.gz';
    
    public function get(array $terms)
    {
        $xml = simplexml_load_string(gzdecode($this->http($this->url)), NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
        return current($xml->xpatH("//SOAP-ENV:Body"))->d2LogicalModel->payloadPublication;    
    }    
}