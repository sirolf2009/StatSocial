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

		$this->load->model('Ndw_model');
	}

	public function index() {

		$roadData = $this->Ndw_model->getWithLocation();

		$this->load->view('layout/header', array('title' => 'Wegendata'));
		$this->load->view('layout/nav', array());
		$this->load->view('pages/roaddata', array("roadData"=> $roadData));
		$this->load->view('layout/footer', array());
	}

	/**
	 * Get actual NDW records and store in db
	 */
	public function get() {
		$this->Ndw_model->getActualData();
	}

}

/* End of file roaddata.php */
/* Location: ./application/controllers/Roaddata.php */