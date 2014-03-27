<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Socialuser_Model extends MY_Model {
    
    protected $table = 'social_users';
    protected $primary_key = 'social_id';
    protected $fields = array('social_id', 'type', 'name');  
    
    public function get()
    {
        $args = func_get_args();
        $this->db->select("excludes.date");
        $this->db->join('excludes', 'excludes.social_id = social_users.social_id AND excludes.type = social_users.type', 'left');
        
        if (count($args) > 0)
        {
            return parent::get($args[0]);    
        } 
        return parent::get();  
    }  
    
    // ------------------------------------------------------------------------
    
    public function get_all()
    {
        $args = func_get_args();
        $this->db->select("excludes.date");
        $this->db->join('excludes', 'excludes.social_id = social_users.social_id AND excludes.type = social_users.type', 'left');
        
        if (count($args) > 0)
        {
            return parent::get_all($args[0]);    
        }
        return parent::get_all();
    }
    
    // ------------------------------------------------------------------------
    
    public function block($type, $id)
    {
        if ( ! in_array($type, array('FACEBOOK', 'TWITTER')))
        {
            return FALSE;
        } 
        
        // inser the user to the exclude list
        $this->db->insert("excludes", array('type' => $type, 'social_id' => $id, 'date' => time()));
        
        $return = $this->db->affected_rows();
        
        // delete all the posts from this user
        $this->db->where(array('type' => $type, 'social_id' => $id));
        $this->db->delete("posts");  
        
        return $return;
    }
    
    // ------------------------------------------------------------------------
    
    public function unblock($type, $id)
    {
        if ( ! in_array($type, array('FACEBOOK', 'TWITTER')))
        {
            return FALSE;
        }
        
        // delete the user from the exclude list
        $this->db->where(array('type' => $type, 'social_id' => $id));
        $this->db->delete("excludes");
        
        return $this->db->affected_rows();
    }
}