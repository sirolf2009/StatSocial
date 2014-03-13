<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_social_user extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('social_user');
        
        $fields = array('social_id' => array('type' => 'INT', 'constraint' => 20, 'null' => TRUE),
                        'type' => array('type' => 'ENUM', 'constraint' => array('TWITTER', 'FACEBOOK'), 'null' => FALSE),
                        'name' => array('type' => 'VARCHAR', 'constraint' => 100 'null' => TRUE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key(array('social_id', 'type'), TRUE);
        $this->dbforge->create_table("social_user");    
    }
    
    public function down()
    {
        $this->dbforge->drop_table('social_user');     
    }
}