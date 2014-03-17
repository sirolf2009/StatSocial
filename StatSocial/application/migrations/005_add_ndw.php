<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_ndw extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('ndw', TRUE); 
        
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'location' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE),
                        'latitude' => array('type' => 'DECIMAL', 'constraint' => '10,8', 'null' => TRUE),
                        'longitude' => array('type' => 'DECIMAL', 'constraint' => '10,8', 'null' => TRUE),
                        'type' => array('type' => 'VARCHAR', 'constraint' => 50, 'null' => FALSE),
                        'description' => array('type' => 'TEXT', 'null' => TRUE),
                        'start_date' => array('type' => 'INT', 'constraint' => 10, 'null' => TRUE),
                        'end_date' => array('type' => 'INT', 'constraint' => 10, 'null' => TRUE),
                        'date' => array('type' => 'INT', 'constraint' => 10, 'null' => FALSE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key("id", TRUE);
        $this->dbforge->create_table("ndw");    
    }
    
    public function down()
    {
        $this->dbforge->drop_table('ndw');     
    }
}