<?php
class Controller_Users_Structure extends Controller_Users {

	public function index() {
		if ($this->registry["ui"]["admin"]) {

			$this->view->setTitle("Structure");

			$this->view->users_subgrouplist();
		}
	}
}
?>