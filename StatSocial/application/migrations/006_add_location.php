<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_location extends CI_Migration {
    
    public function up()
    {
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'type' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => FALSE),
                        'roadnumber' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => FALSE),
                        'roadname' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => FALSE),
                        'first_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE),
                        'second_name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key("id", TRUE);
        $this->dbforge->create_table("location");
    }
    
    public function down()
    {
        $this->dbforge->drop_table('location');    
    }
}