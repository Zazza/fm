<?php
class Controller_Users_EditGroup extends Controller_Users {

	public function index() {
		if ($this->registry["ui"]["admin"]) {

			$this->view->setTitle("Пользователи");
			 
			if (isset($this->args[1])) {
				$gname = $this->muser->getGroupName($this->args[1]);
				 
				if (isset($_POST['editgroup'])) {
					$this->muser->editGroupName($this->args[1], $_POST["group"]);
					 
					$this->view->refresh(array("timer" => "1", "url" => "users/"));
				} else {
					$this->view->users_editgroup(array("gname" => $gname));
				}
			}
		}
	}
}
?>