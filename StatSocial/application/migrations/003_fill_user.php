<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Fill_user extends CI_Migration {
    
    public function up()
    {
        $data = array(array('id' => 1, 'name' => 'Ditmar Commandeur', 'email' => 'ditmar.commandeur@gmail.com', 'password' => 'aba9474fbc1ca2f60775dea1EM83x6DVTOuhTyNZatTDoHMKGg2H6', 'last_login' => 1394540877),
                      array('id' => 2, 'name' => 'Floris Thijssen', 'email' => 'masterflappie@gmail.com', 'password' => 'c1b305c9aea14f5e69b5dOYyDWjWoeSetJ.5ejdLiNvigP6FMCRbm', 'last_login' => 1394545594),
                      array('id' => 3, 'name' => 'Coen den Engelsman', 'email' => 'coendenengelsman@gmail.com', 'password' => 'c98340ab30eb403792c0auPAz3Fmr718DzJAVQEBA1T7ZuuNfbEv.', 'last_login' => 1394540788),
                      array('id' => 4, 'name' => 'Gokhan Kacan', 'email' => 'gokhankacan83@gmail.com', 'password' => '97cdb01333a62d764f48bufb4JFE9u0YzBE/AY7SaWzfJzGyEmIUa', 'last_login' => 1394540788));
        
        $this->db->insert_batch('user', $data);        
    }

    public function down()
    {
        $this->db->where_in('id', array(1, 2, 3, 4));
        $this->db->delete('user');    
    }
}