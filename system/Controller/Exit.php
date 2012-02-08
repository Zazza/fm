<?php
class Controller_Exit extends Engine_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->view->setTitle("Выход");
		
		$ui = new Model_Ui();

		session_destroy();

		$this->view->refresh(array("timer" => "1", "url" => ""));
	}
}
?>