<?php
class Controller_Exit extends Engine_Controller {
	public function index() {
		$this->view->setTitle("Exit");

		session_destroy();

		echo $this->view->render("refresh", array("timer" => "1", "url" => ""));
		
		exit();
	}
}
?>