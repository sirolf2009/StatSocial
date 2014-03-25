<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Fill_location extends CI_Migration {
    
    public function up()
    {
        $file = $this->load->file(APPPATH.'migrations/locations.sql', true);

        $this->db->trans_start();
        
        foreach(explode(';', $file) as $query)
        {
            $this->db->query($query);
        }
        
        $this->db->trans_complete();     
    }
    
    public function down()
    {
        $this->db->truncate('locations');
    }
}