<?php
class Controller_Users_List extends Controller_Users {

	public function index() {
		$this->view->setTitle("Пользователи");

		$groups = $this->muser->getGroups();
		$uniq_groups = $this->muser->getUniqGroups();
		$users = $this->muser->getUsersGroups();

		$sortlist = array();
		foreach($groups as $group) {
			foreach($users as $user) {
				if ( ($user["gname"] == $group["sname"]) and ($group["sname"] != null) ) {
					$udata = $this->view->render("users_data", array("data" => $this->muser->getUserInfo($user["id"])));
					$sortlist[$group["pname"]][$group["sid"]][] = $udata;
				}
			}
		}

		$this->print_array($sortlist);

		$this->view->users_tree(array("group" => $uniq_groups, "list" => $this->tree));
	}
}
?>