<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class PostModel extends MY_Model {
    
    protected $table = 'posts';
    protected $primary_key = 'id';
    protected $fields = array('id', 'post_id', 'ndw_id', 'social_id', 'type', "message", "location", "date");
    
    public function addTweet($data) {
        if(self::doesTweetExist($data) == false) {
            $date = intval(date('d', strtotime($data->created_at)));
            $row = array("post_id" => intval($data->id), "ndw_id" => -1, "social_id" => self::getOrCreateSocialUser($data->user, "TWITTER"), "type" => "TWITTER", "message" => $data->text, "location" => $data->coordinates, "date" => $date);
            return parent::insert($row, true);
        }
    }

    public function doesTweetExist($data) {
        return !is_null(parent::get("post_id", intval($data->id)));
    }

    public function getOrCreateSocialUser($userObject, $type) {
        if($type == "TWITTER") {
            $user = parent::get("type='TWITTER' AND social_id=".$userObject->id);
            if(is_null($user)) {
                $table = "social_users";
                $fields = array("social_id", "type", "name");
                $primary_key = "social_id";
                parent::insert(array('social_id' => intval($userObject->id), "type" => "TWITTER", "name" => $userObject->name));
                $table = 'posts';
                $primary_key = 'id';
                $fields = array('id', 'post_id', 'ndw_id', 'social_id', 'type', "message", "location", "date");
            }
            return $userObject->id;
        } elseif($type == "FACEBOOK") {

        } else {
            //lolhacks cheatsydoodles
        }
    }
}