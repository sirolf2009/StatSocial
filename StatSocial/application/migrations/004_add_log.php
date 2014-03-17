<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_log extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('logs', TRUE);
        
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'type' => array('type' => 'ENUM', 'constraint' => array('FACEBOOK', 'TWITTER', 'NDW'), 'null' => FALSE),
                        'request' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => FALSE),
                        'answer' => array('type' => 'TEXT', 'null' => FALSE),
                        'date' => array('type' => 'INT', 'constraint' => 10, 'null' => FALSE));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key("id", TRUE);
        $this->dbforge->create_table("logs");
    }
    
    public function down()
    {
        $this->dbforge->drop_table('logs');    
    }
}