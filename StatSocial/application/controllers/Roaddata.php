<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Roaddata
 * @property Ndw_model $Ndw_model
 */
class Roaddata extends CI_Controller {

	public function __construct() {
		parent::__construct();

		if ($this->auth->is() !== true) {
			redirect('/');
		}
	}

	public function index() {
		$this->load->view('layout/header', array('title' => 'Wegendata'));
		$this->load->view('layout/nav', array());
		$this->load->view('pages/roaddata', array());
		$this->load->view('layout/footer', array());
	}

	/**
	 * Get actual NDW records and store in db
	 */
	public function get() {
		$this->load->driver('request');
		$this->request->select_driver('ndw');
		$result = $this->request->get();
		$ndw = array();
		foreach ($result->situation as $situation) {
			$ndwRec          = array();
			$situationRec = $situation->situationRecord;

			$location        = $situationRec->groupOfLocations->locationContainedInItinerary->location;
			$ndwRec['location'] = null;
			if (isset($location->alertCLinear->alertCMethod4PrimaryPointLocation->alertCLocation->specificLocation)) {
				$ndwRec['location'] = (int)$location->alertCLinear->alertCMethod4PrimaryPointLocation->alertCLocation->specificLocation;
			}

			$ndwRec['latitude']  = (float)$location->locationForDisplay->latitude;
			$ndwRec['longitude'] = (float)$location->locationForDisplay->longitude;

			$cause              = $situationRec->cause;
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

		$this->load->model('Ndw_model');
		$this->Ndw_model->insertBatch($ndw); //TODO check duplication
	}
}

/* End of file roaddata.php */
/* Location: ./application/controllers/Roaddata.php */