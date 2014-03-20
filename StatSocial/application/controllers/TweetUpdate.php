<?php defined('BASEPATH') OR exit('No direct script access allowed');

class TweetUpdate extends MY_Controller {

    public function __construct() {
        parent::__construct();
        
        if ($this->auth->is() !== TRUE) {
            redirect('/');
        }
    }
    
    public function index() {

        $this->load->library('sentiment');
        $this->load->driver('request');
        $this->request->select_driver('twitter');
        $this->load->model("postModel");
        $this->postModel->processAllRoads();

		$this->viewPage("TweetUpdate");
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