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
        $this->load->helper('form');
	}

	public function index() {

        $order_by = 'DESC';
        $limit    = 25;
        
        if ($this->input->post('type') && $this->input->post('type') !== 'ALL')
        {
            $this->Ndw_model->where('ndw.type', str_replace('unknown', '', $this->input->post('type', TRUE)));    
        }
        
        if ($this->input->post('date_from'))
        {
            $this->Ndw_model->where('ndw.date >=', strtotime($this->input->post('date_from', TRUE)));    
        }
        
        if ($this->input->post('date_to'))
        {
            $this->Ndw_model->where('ndw.date <=', strtotime($this->input->post('date_to', TRUE)) + 86399);   
        }
        
        if ($this->input->post('date'))
        {
            $order_by = $this->input->post('date');  
        }
        
        if ($this->input->post('amount'))
        {
            $limit = ($this->input->post('amount') !== 'ALL' ? $this->input->post('amount') : 99999999);    
        }
        
		$data['roadData'] = $this->Ndw_model->getWithLocation($order_by, $limit);
        $data['roads']    = array('A1', 'A2', 'A4', 'A5', 'A6', 'A7', 'A8', 'A9', 'A10', 'A12', 'A13', 'A15', 'A16', 'A17', 'A18', 'A20', 'A22', 'A27', 'A28', 'A30', 'A31', 'A32', 'A35', 'A37', 'A38', 'A44', 'A50', 'A58', 'A59', 'A65', 'A67', 'A73', 'A74', 'A76', 'A77', 'A79', 'A200', 'N3', 'N7', 'N9', 'N11', 'N14', 'N18', 'N31', 'N32', 'N33', 'N34', 'N35', 'N36', 'N37', 'N44', 'N48', 'N50', 'N57', 'N58', 'N59', 'N61', 'N65', 'N69', 'N99', 'N200', 'N259', 'N271', 'N273');
        $data['logger']   = $this->logger->last('NDW');

		$this->addJs("ndw_charts.js");
		$this->addView('pages/roaddata', $data);
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
    
    public function getPostData($medium = FALSE){
        $data = $this->Ndw_model->getRoadsWithPostCount($medium);
        $json = array('roads' => array());

        foreach ($data as $d) {
            if ($medium) {
                $json[$d['roadnumber']][strtolower($d['type'])] = (int)$d['count'];
                
                if (! in_array($d['roadnumber'], $json['roads'])) {
                    $json['roads'][] = $d['roadnumber'];
                }
            }
            else {
                $json['count'][] = (int)$d['count'];
                $json['roads'][] = $d['roadnumber'];    
            }
        }
        
        if ($medium)
        {
            foreach ($json['roads'] as $road)
            {
                $json['twitter'][] = isset($json[$road]['twitter']) ? $json[$road]['twitter'] : 0;
                $json['facebook'][] = isset($json[$road]['facebook']) ? $json[$road]['facebook'] : 0;
                
                unset($json[$road]);
            }
        }

        header('Content-type: application/json');
        echo json_encode($json);    
    }
    
    public function getSentimentData(){
        $data = $this->Ndw_model->getRoadsWithSentiment();
        $json = array('roads' => array());
        
        foreach ($data as $d)
        {
            $json['roads'][] = $d['roadnumber'];
            $json['positive'][] = round($d['positive'], 0);
            $json['negative'][] = round($d['negative'], 0);
            $json['average'][] = round($d['average'], 0);
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