<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_post extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('posts');
        
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'post_id' => array('type' => 'INT', 'constraint' => 20, 'null' => FALSE),
                        'ndw_id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE),
                        'social_id' => array('type' => 'INT', 'constraint' => 20, 'null' => TRUE),
                        'type' => array('type' => 'ENUM', 'constraint' => array('TWITTER', 'FACEBOOK'), 'null' => FALSE),
                        'message' => array('type' => 'text', 'null' => TRUE),
                        'location' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                        'date' => array('type' => 'INT', 'constraint' => 10, 'null' => FALSE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key("id", TRUE);
        $this->dbforge->create_table("posts");    
    }
    
    public function down()
    {
        $this->dbforge->drop_table('posts');     
    }
}