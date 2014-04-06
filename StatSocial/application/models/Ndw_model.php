<?php

class Ndw_model extends MY_Model {
	protected $table = 'ndw';
	protected $primary_key = 'id';
	protected $fields = array('id', 'situation_id', 'location', 'latitude', 'longitude', 'type', 'description', 'start_date', 'end_date', 'date');
    
    private $result = NULL;

	/**
	 * Insert a batch of ndw records into the db
	 * @param array $data
	 */
	public function insertBatch($data) {
		$this->db->insert_batch($this->table, $data);
	}

	public function getWithLocation($order = 'DESC', $limit = NULL) {
		$this->db->select("locations.*, ndw.*, locations.type as location_type");
		$this->db->join("locations", "ndw.location = locations.id");
		$this->db->order_by("ndw.date", $order);
        $this->db->limit($limit);
		return $this->result = $this->db->get("ndw")->result_array();
	}
    
    public function getRecentWithLocation()
    {
        $this->db->select("locations.*, ndw.*, locations.type as location_type");
        $this->db->join("locations", "ndw.location = locations.id");
        $this->db->order_by("ndw.date", "DESC");
        $this->db->where("ndw.end_date >=", strtotime("-1 hour"));
        
        return $this->db->get("ndw")->result();
    }

	/**
	 * Get ndw types
	 * @return mixed
	 */
	public function getTypes(){
		$this->db->select('type');
		$this->db->group_by('type');
		$data = $this->db->get('ndw')->result_array();
		$return = array();
		foreach($data as $d){
			$return[] = $d['type'];
		}
		return $return;
	}

	public function getTypesWithCount($emptyNot = false){
		if($emptyNot){
			$this->db->where("type <> ''");
		}
		$this->db->select('`type`, count(`type`) as `count`');
		$this->db->group_by('type');
		return $this->db->get('ndw')->result_array();
	}

	/**
	 * @param null $type
	 * @param int $top
	 * @return mixed
	 */
	public function getRoadsWithCount($type = null, $top = 20){
		$this->db->select("roadnumber, count(ndw.id) as count");
		$this->db->join("locations", "ndw.location = locations.id");
		if($type){
			$this->db->where("ndw.type", $type);
		}
		$this->db->group_by("roadnumber");
		$this->db->order_by("count", "DESC");
		$this->db->limit($top);
		return $this->db->get("ndw")->result_array();
	}

	private function existId($situationId) {
		$this->db->where("situation_id", $situationId);
		$this->db->from('ndw');
		return $this->db->count_all_results() == '0' ? false : true ;
	}
    
    public function getRoadsWithPostCount($medium = FALSE)
    {
        $this->db->select("locations.roadnumber, COUNT(posts.id) AS count, posts.type");
        $this->db->from('posts');
        $this->db->join('ndw', 'posts.ndw_id = ndw.id');
        $this->db->join('locations', 'locations.id = ndw.location');
        if ($medium)
        {
            $this->db->group_by('posts.type');
        }
        $this->db->group_by('locations.roadnumber');
        $this->db->order_by('count', 'DESC');
        $this->db->limit(20);
        return $this->db->get()->result_array();
    }
    
    public function getRoadsWithSentiment()
    {
        $this->db->select("locations.roadnumber, SUM(posts.positive) AS positive, SUM(posts.negative) AS negative, (SUM(posts.positive + posts.negative)/2) AS average");
        $this->db->from('posts');
        $this->db->join('ndw', 'posts.ndw_id = ndw.id');
        $this->db->join('locations', 'locations.id = ndw.location');
        $this->db->group_by('locations.roadnumber');
        $this->db->limit(20);
        return $this->db->get()->result_array();
    }

	public function getActualData() {
		$this->load->driver('request');
		$this->request->select_driver('ndw');
		$result = $this->request->get();
        
        // log request 
        $this->logger->add('NDW', 'default request URL');
        
		$ndw    = array();
		foreach ($result->situation as $situation) {

			$ndwRec = array();
			if ($this->existId((string)$situation['id'])) {
				continue;
			}

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
				if($ndwRec['type'] == 'other'){
					$ndwRec['type'] = $ndwRec['description'];
				}
			}

			$ndwRec['date']       = strtotime((string)$situationRec->situationRecordCreationTime);
			$ndwRec['start_date'] = strtotime((string)$situationRec->validity->validityTimeSpecification->overallStartTime);
			$ndwRec['end_date']   = strtotime((string)$situationRec->validity->validityTimeSpecification->overallEndTime);

			$ndw[] = $ndwRec;
		}

		if ($ndw) {
			if (count($ndw) == 1) {
				$this->insert($ndw[0]);
			} else {
				$this->db->insert_batch($this->table, $ndw);

			}
		}
	}
}