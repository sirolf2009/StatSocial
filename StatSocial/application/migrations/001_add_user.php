<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Add_user extends CI_Migration {
    
    public function up()
    {
        $this->dbforge->drop_table('users', TRUE);
        
        $fields = array('id' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'auto_increment' => TRUE),
                        'name' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => FALSE),
                        'email' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => FALSE),
                        'password' => array('type' => 'VARCHAR', 'constraint' => 64, 'null' => FALSE),
                        'last_login' => array('type' => 'INT', 'constraint' => 10, 'null' => TRUE, 'default' => 1300000000));
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key("id", TRUE);
        $this->dbforge->create_table("users");
        // small fix for the unique index on email field.
        $this->db->query("ALTER TABLE `users` ADD UNIQUE INDEX `email` (`email`);");
    }
    
    public function down()
    {
        $this->dbforge->drop_table('users');    
    }
}