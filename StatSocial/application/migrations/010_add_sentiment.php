<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_sentiment extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('sentiments', TRUE);
        
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'type' => array('type' => 'ENUM', 'constraint' => array('POSITIVE', 'NEGATIVE'), 'null' => FALSE),
                        'regex' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => FALSE),
                        'false_positive' => array('type' => 'DECIMAL', '16,15', 'null' => TRUE, 'default' => 0),
                        'false_negative' => array('type' => 'DECIMAL', '16,15', 'null' => TRUE, 'default' => 0));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table("sentiments");    
        $this->db->query("ALTER TABLE `sentiments` ADD UNIQUE INDEX `unique_regex` (`regex`);");
        $this->db->query("ALTER TABLE `sentiments` ADD FULLTEXT INDEX `regex` (`regex`);");
    }
    
    public function down()
    {
        $this->dbforge->drop_table('sentiments');     
    }
}