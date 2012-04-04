<?php
class Controller_Users_Adduser extends Controller_Users {

	public function index() {
		if ($this->registry["ui"]["admin"]) {

			$this->view->setTitle("New user");

			if (isset($_POST['adduser'])) {
				$validate = new Model_Validate();
				
				if (!is_numeric($_POST["quota_val"])) {
					$res_val = 100;
				} else {
					$val = $_POST["quota_val"];
				}				
				if ($_POST["quota_unit"] == "mb") {
					$res_val = $val * 1024 * 1024;
				}
				if ($_POST["quota_unit"] == "gb") {
					$res_val = $val * 1024 * 1024 * 1024;
				}
				 
				$err = array();
				if ($txt = $validate->login($_POST["login"])) { $err[] = $txt; };
				if ($txt = $validate->password($_POST["pass"])) { $err[] = $txt; };

				if (count($err) == 0) {
					 
					if (!isset($_POST["notify"])) {
						$notify = 0;
					} else {
						$notify = 1;
					}

					$uid = $this->muser->addUser($_POST["login"], $_POST["pass"], $res_val);
					$this->muser->addUserPriv($uid, $_POST["priv"], $_POST["gid"]);
					 
					$this->view->refresh(array("timer" => "1", "url" => "users/"));
				} else {
					$group = $this->muser->getGroups();
					$this->view->users_adduser(array("group" => $group, "err" => $err, "post" => $_POST));
				}
			} else {
				$group = $this->muser->getGroups();
				$post["time_notify"] = "08:00:00";
				$this->view->users_adduser(array("group" => $group, "post" => $post));
			}
		}
	}
}
?>