<?php
class Ndw_model extends MY_Model{
	protected $table = 'ndw';
	protected $primary_key = 'id';
	protected $fields = array('id', 'location', 'latitude', 'longitude', 'type', 'description', 'start_date', 'end_date', 'date');

	/**
	 * Insert a batch of ndw records into the db
	 * @param $data
	 */
	public function insertBatch($data){
		$this->db->insert_batch($this->table, $data);
	}
} 