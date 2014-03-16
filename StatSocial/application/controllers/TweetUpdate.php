<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TweetUpdate extends CI_Controller {

    public static $ARoads = array("A1", "A13", "A30", "A65", "A2", "A15", "A31", "A67", "A4", "A16", "A32", "A73", "A5", "A17", "A35", "A74", "A6", "A18", "A37", "A76", "A7", "A20", "A38", "A77", "A8", "A22", "A44", "A79", "A9", "A27", "A50", "A200", "A10", "A28", "A58", "A12", "A29", "A59");
    public static $NRoads = array("N3", "N32", "N48", "N69", "N7", "N33", "N50", "N99", "N9", "N34", "N57", "N200", "N11", "N35", "N58", "N259", "N14", "N36", "N59", "N271", "N18", "N37", "N61", "N273", "N31", "N44", "N65");

    public function __construct() {
        parent::__construct();
        
        if ($this->auth->is() !== TRUE) {
            redirect('/');
        }
    }
    
    public function index() {
        $this->load->view('layout/header', array('title' => 'TweetUpdate'));
        $this->load->view('layout/nav', array());

        $this->load->library('sentiment');
        $this->load->driver('request');
        $this->request->select_driver('twitter');
        $this->load->model("postModel");

        foreach (self::$ARoads as $road) {
            $result = self::processTweetList($this->request->get(array('q' => 'file '.$road, 'result_type' => 'recent', 'lang' => 'nl')));
        }

        $this->load->view('layout/footer', array());
    }

    public function processTweetList($tweet) {
        for($i = 0; $i < sizeof($tweet->statuses); $i++) {
            print($i."<br>".sizeof($tweet->statuses)."<br>");
            $this->postModel->addTweet($tweet->statuses[$i]);
        }
    }
}

/*
statuses !array
 metadata !object
 created_at !string
 id !double
 id_str !string
 text !string
 source !string
 truncated !boolean
 in_reply_to_status_id !NULL
 in_reply_to_status_id_str !NULL
 in_reply_to_user_id !NULL
 in_reply_to_user_id_str !NULL
 in_reply_to_screen_name !NULL
 user !object
 geo !NULL
 coordinates !NULL
 place !NULL
 contributors !NULL
 retweeted_status !object
 retweet_count !integer
 favorite_count !integer
 entities !object
 favorited !boolean
 retweeted !boolean
 lang !string
search_metadata !object
 completed_in !double
 max_id !double
 max_id_str !string
 query !string
 refresh_url !string
 count !integer
 since_id !integer
 since_id_str !string
*/