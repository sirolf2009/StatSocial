<?php defined("BASEPATH") OR exit("No direct script access allowed.");

class Migration_Change_Ndw extends CI_Migration {
    
    public function up()
    {
        $this->db->query("ALTER TABLE `ndw` ADD COLUMN `situation_id` VARCHAR(25) NOT NULL AFTER `id`;");
		$this->db->query("ALTER TABLE `ndw` ADD UNIQUE INDEX `situation_id` (`situation_id`);");
    }
    
    public function down()
    {
		$this->db->query("ALTER TABLE `ndw`	DROP COLUMN `situation_id`;");
    }
}