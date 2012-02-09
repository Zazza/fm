<?php
class Controller_Index extends Engine_Controller {

	public function index() {
		$this->view->setTitle("Файловый менеджер");
		
		$this->view->index();
	}
}
?>