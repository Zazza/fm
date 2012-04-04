<?php
class Controller_Statistic extends Engine_Controller {
	function index() {
		$this->view->setTitle("Statistics");
		
		if ($this->registry["ui"]["admin"]) {
			$users = new Model_User();
			
			$data = $users->getTotal();
			
			if (($data["all"] / 1024 / 1024) > 1) {
				$data["all_val"] = round($data["all"] / 1024 / 1024, 2);
				$data["all_unit"] = "mb";
			};
			if (($data["all"] / 1024 / 1024 / 1024) > 1) {
				$data["all_val"] = round($data["all"] / 1024 / 1024 / 1024, 2);
				$data["all_unit"] = "gb";
			};
			
			foreach($data["users"] as $part) {
				//$user[$part["login"]]["quota"] = $part["quota"];
				
				if (($part["quota"] / 1024 / 1024) > 1) {
					$user[$part["login"]]["quota_val"] = round($part["quota"] / 1024 / 1024, 2);
					$user[$part["login"]]["quota_unit"] = "mb";
				};
				if (($part["quota"] / 1024 / 1024 / 1024) > 1) {
					$user[$part["login"]]["quota_val"] = round($part["quota"] / 1024 / 1024 / 1024, 2);
					$user[$part["login"]]["quota_unit"] = "gb";
				};
				
				if (($part["sum"] / 1024 / 1024) > 1) {
					$user[$part["login"]]["val"] = round($part["sum"] / 1024 / 1024, 2);
					$user[$part["login"]]["unit"] = "mb";
				};
				if (($part["sum"] / 1024 / 1024 / 1024) > 1) {
					$user[$part["login"]]["val"] = round($part["sum"] / 1024 / 1024 / 1024, 2);
					$user[$part["login"]]["unit"] = "gb";
				};
				
				$user[$part["login"]]["percent"] = round($part["sum"] / $part["quota"] * 100, 0);
			}
			
			$this->view->users_statistic(array("total" => $data, "users" => $user));
		}
	}
}
?>
