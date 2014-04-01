<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Roaddata
 * @property Ndw_model $Ndw_model
 */
class Roaddata extends MY_Controller {

	public function __construct() {
		parent::__construct();

		if ($this->auth->is() !== true) {
			redirect('/');
		}

		$this->load->model('Ndw_model');
	}

	public function index() {

		$roadData = $this->Ndw_model->getWithLocation();

		$this->addJs("ndw_charts.js");
		$this->addView('pages/roaddata', array("roadData" => $roadData));
		$this->viewPage("RoadData");


	}

	/**
	 * Get actual NDW records and store in db
	 */
	public function get() {
		$this->Ndw_model->getActualData();
		redirect(site_url("roaddata"));
	}

	/**
	 * Get data for chart
	 */
	public function getData() {
		$json = array(
			"road" => array(),
			"count" => array()
		);

		$typesToLoad = $this->Ndw_model->getTypes();
		$typesAndCount = array();
		foreach ($typesToLoad as $type) {
			$typesAndCount[$type] = $this->transformData($this->Ndw_model->getRoadsWithCount($type));
		}

		foreach ($this->Ndw_model->getRoadsWithCount() as $road) {
			$json["road"][] = $road["roadnumber"];
			$json["count"][] = (int)$road["count"];
			foreach ($typesToLoad as $type) {
				$json = $this->mergeCounts($road, $typesAndCount[$type], $json, $type);
			}
		}

		header('Content-type: application/json');
		echo json_encode($json);
	}

	public function getDataTypesChart(){
		$data = $this->Ndw_model->getTypesWithCount();
		$json = array();
		foreach($data as $d){
			$json[] = array("name"=>$d['type'], "y" => (int)$d['count']);
		}
		header('Content-type: application/json');
		echo json_encode($json);
	}

	/**
	 * Transform data for chart
	 * @param $roadAndCount
	 * @return array
	 */
	private function transformData($roadAndCount) {
		$arr = array(
			"road" => array(),
			"count" => array()
		);
		foreach ($roadAndCount as $rac) {
			$arr["road"][] = $rac["roadnumber"];
			$arr["count"][] = $rac["count"];
		}
		return $arr;
	}

	/**
	 * merge counts of other types with json array
	 * @param $road
	 * @param $counts
	 * @param $json
	 * @param $type
	 * @return mixed
	 */
	private function mergeCounts($road, $counts, $json, $type) {
		$key = array_search($road["roadnumber"], $counts["road"]);
		if (is_integer($key)) {
			$json[$type][] = (int)$counts["count"][$key];
			return $json;
		} else {
			$json[$type][] = 0;
			return $json;
		}
	}


}

/* End of file roaddata.php */
/* Location: ./application/controllers/Roaddata.php */