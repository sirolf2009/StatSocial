<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class PostModel extends MY_Model {
    
    protected $table = 'posts';
    protected $primary_key = 'id';
    protected $fields = array('id', 'post_id', 'ndw_id', 'social_id', 'type', "message", "location", "date");

    public static $ARoads = array("A1", "A13", "A30", "A65", "A2", "A15", "A31", "A67", "A4", "A16", "A32", "A73", "A5", "A17", "A35", "A74", "A6", "A18", "A37", "A76", "A7", "A20", "A38", "A77", "A8", "A22", "A44", "A79", "A9", "A27", "A50", "A200", "A10", "A28", "A58", "A12", "A29", "A59");
    public static $NRoads = array("N3", "N32", "N48", "N69", "N7", "N33", "N50", "N99", "N9", "N34", "N57", "N200", "N11", "N35", "N58", "N259", "N14", "N36", "N59", "N271", "N18", "N37", "N61", "N273", "N31", "N44", "N65");

    public function processAllRoads() {
        foreach (self::$ARoads as $road) {
            self::processSingleRoad($road);
        }
        foreach ($this->$NRoads as $road) {
            self::processSingleRoad($road);
        }
    }

    public function processSingleRoad($road) {
        self::processTweetList($this->request->get(array('q' => 'file '.$road, 'result_type' => 'recent', 'lang' => 'nl')));
    }

    public function processTweetList($tweet) {
        if(isset($tweet) && isset($tweet->statuses)) {
            foreach ($tweet->statuses as $data) {
                if(isset($data)) {
                    self::addTweet($data);
                }
            }
        }
    }
    
    public function addTweet($data) {
        $existence = self::doesTweetExist($data);
        if($existence == false) {
            $date = strtotime($data->created_at);
            $coords = isset($data->coordinates) ? implode(", ", $data->coordinates->coordinates) : null;
            $row = array("post_id" => $data->id_str, "ndw_id" => -1, "social_id" => self::getOrCreateSocialUser($data->user, "TWITTER"), "type" => "TWITTER", "message" => $data->text, "location" => $coords, "date" => $date);
            return parent::insert($row, true);
        }
    }

    public function doesTweetExist($data) {
        return !is_null(parent::get("post_id", ($data->id_str)));
    }

    public function getOrCreateSocialUser($userObject, $type) {
        if($type == "TWITTER") {
            $user = $this->db->get("social_users", array('social_id' => $userObject->id_str));
            if($user->num_rows() == 0) {
                $this->db->insert("social_users", array('social_id' => $userObject->id_str, "type" => "TWITTER", "name" => $userObject->name));
            }
            return ($userObject->id_str);
        } elseif($type == "FACEBOOK") {

        } else {
            //lolhacks cheatsydoodles
        }
    }
}