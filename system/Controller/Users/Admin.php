<?php
class Controller_Users_Admin extends Controller_Users {

	public function index() {
		if ($this->registry["ui"]["admin"]) {

			$this->view->setTitle("Управление пользователями");

			$uniq_groups = $this->muser->getUniqGroups();
			
			$this->view->users_admin(array("group" => $uniq_groups));
		}
	}
}
?>