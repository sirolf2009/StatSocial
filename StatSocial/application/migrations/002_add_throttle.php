<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_throttle extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('throttle');
        
        $fields = array('user' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE),
                        'ipaddress' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => FALSE),
                        'attempts' => array('type' => 'INT', 'constraint' => 1, 'null' => TRUE),
                        'last_attempt' => array('type' => 'INT', 'constraint' => 10, 'null' => TRUE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key(array("user", "ipaddress"), TRUE);
        $this->dbforge->create_table("throttle");
    }
    
    public function down()
    {
        $this->dbforge->drop_table('throttle');    
    }
}