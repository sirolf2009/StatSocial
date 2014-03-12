<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_post extends CI_Migration {
    
    public function up()
    {
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'postid' => array('type' => 'INT', 'constraint' => 20, 'null' => FALSE),
                        'ndwid' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE),
                        'userid' => array('type' => 'INT', 'constraint' => 20, 'null' => TRUE),
                        'type' => array('type' => 'ENUM', 'constraint' => array('TWITTER', 'FACEBOOK'), 'null' => FALSE),
                        'message' => array('type' => 'text', 'null' => TRUE),
                        'location' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                        'date' => array('type' => 'INT', 'constraint' => 10, 'null' => FALSE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key("id", TRUE);
        $this->dbforge->create_table("post");    
    }
    
    public function down()
    {
        $this->dbforge->drop_table('post');     
    }
}