<?php
class Controller_Users_Edituser extends Controller_Users {

	public function index() {
		if ($this->registry["ui"]["admin"]) {

			$this->view->setTitle("Пользователи");

			if (isset($_POST['edituser'])) {
				$group = $this->muser->getGroups();
				$data = $this->muser->getUserInfo($this->args[1]);

				$validate = new Model_Validate();
				 
				$err = array();
				if ($_POST["login"] != $data["login"]) {
					if ($txt = $validate->login($_POST["login"])) { $err[] = $txt; };
				}
				if ($data["pass"] != $_POST["pass"]) {
					if ($txt = $validate->password($_POST["pass"])) { $err[] = $txt; };
				}

				if (count($err) == 0) {
					 
					if (!isset($_POST["notify"])) {
						$notify = 0;
					} else {
						$notify = 1;
					}
					 
					$uid = $this->muser->editUser($this->args[1], $_POST["login"]);
					if ($data["pass"] != $_POST["pass"]) {
						$this->muser->editUserPass($this->args[1], $_POST["pass"]);
					}
					 
					$this->muser->editUserPriv($this->args[1], $_POST["priv"], $_POST["gid"]);

					$this->view->refresh(array("timer" => "1", "url" => "users/"));
				} else {
					$_POST["uid"] = $data["uid"];
					$this->view->users_edituser(array("group" => $group, "err" => $err, "post" => $_POST));
				}
			} else {
				$data = $this->muser->getUserInfo($this->args[1]);
				$group = $this->muser->getGroups();

				if ($data["admin"]) {
					$data["priv"] = "admin";
				}
				 
				$this->view->users_edituser(array("post" => $data, "group" => $group));
			}
		}
	}
}
?>