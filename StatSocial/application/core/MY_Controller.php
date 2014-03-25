<?php defined("BASEPATH") OR exit("No direct script access allowed.");

/**
 * Class MY_Controller
 */
class MY_Controller extends CI_Controller {

	private $views = array();
	private $js = array();

	public function __construct() {
		parent::__construct();
	}

	/**
	 * Add an view to the list of views
	 * @param $view
	 * @param $data
	 */
	public function addView($view, $data = array()) {
		$this->views[] = array(
			"view" => $view,
			"data" => $data
		);
	}

	/**
	 * add a js file to load
	 * @param $file
	 */
	public function addJs($file) {
		$this->js[] = $file;
	}

	/**
	 * View the page
	 * @param string $title title van de pagina
	 */
	public function viewPage($title = "") {
		$this->load->view('layout/header', array('title' => $title));
        if($this->auth->is()){
            $this->load->view('layout/nav', array());
        }

		foreach ($this->views as $view) {
			$this->load->view($view["view"], $view["data"]);
		}
		$this->load->view('layout/footer', array("js" => $this->js));
	}

}