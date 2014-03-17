<?php
class Ndw_model extends MY_Model{
	protected $table = 'ndw';
	protected $primary_key = 'id';
	protected $fields = array('id', 'location', 'latitude', 'longitude', 'type', 'description', 'start_date', 'end_date', 'date');

	/**
	 * Insert a batch of ndw records into the db
	 * @param array $data
	 */
	public function insertBatch($data){
		$this->db->insert_batch($this->table, $data);
	}

	public function getWithLocation(){
		$this->db->join("location", "ndw.location = location.id");
		return $this->db->get("ndw")->result_array();
	}

	public  function getActualData() {
		$this->load->driver('request');
		$this->request->select_driver('ndw');
		$result = $this->request->get();
		$ndw    = array();
		foreach ($result->situation as $situation) {

			$ndwRec       = array();

			$ndwRec["situation_id"] = (string)$situation['id'];
			$situationRec = $situation->situationRecord;

			$location           = $situationRec->groupOfLocations->locationContainedInItinerary->location;
			$ndwRec['location'] = null;
			if (isset($location->alertCLinear->alertCMethod4PrimaryPointLocation->alertCLocation->specificLocation)) {
				$ndwRec['location'] = (int)$location->alertCLinear->alertCMethod4PrimaryPointLocation->alertCLocation->specificLocation;
			} else {
				continue;
			}

			$ndwRec['latitude']  = (float)$location->locationForDisplay->latitude;
			$ndwRec['longitude'] = (float)$location->locationForDisplay->longitude;

			$cause                 = $situationRec->cause;
			$ndwRec['type']        = (string)$cause->causeType;
			$ndwRec['description'] = "";
			if (isset($cause->causeDescription->values)) {
				$ndwRec['description'] = (string)$cause->causeDescription->values[0]->value;
			}

			$ndwRec['date']       = strtotime((string)$situationRec->situationRecordCreationTime);
			$ndwRec['start_date'] = strtotime((string)$situationRec->validity->validityTimeSpecification->overallStartTime);
			$ndwRec['end_date']   = strtotime((string)$situationRec->validity->validityTimeSpecification->overallEndTime);

			$ndw[] = $ndwRec;
		}

		$this->Ndw_model->insertBatch($ndw); //TODO check duplication
	}
} 