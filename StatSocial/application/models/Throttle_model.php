<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Throttle_Model extends MY_Model {
    
    protected $table = 'throttle';
    protected $primary_key = 'user';
    protected $fields = array('user', 'ipaddress', 'attempts', 'last_attempt');                         
}