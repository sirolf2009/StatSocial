<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_exclude extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('excludes', TRUE);
        
        $fields = array('social_id' => array('type' => 'BIGINT', 'constraint' => 20, 'null' => TRUE),
                        'type' => array('type' => 'ENUM', 'constraint' => array('TWITTER', 'FACEBOOK'), 'null' => FALSE),
                        'date' => array('type' => 'INT', 'constraint' => 10, 'null' => FALSE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key(array('social_id', 'type'), TRUE);
        $this->dbforge->create_table("excludes");    
    }
    
    public function down()
    {
        $this->dbforge->drop_table('excludes');     
    }
}