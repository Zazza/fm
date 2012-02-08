<?php
class Model_Ui extends Engine_Model {

	public function login($login, $pass) {
		$sql = "SELECT * FROM users WHERE login = :login AND pass != '' LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if (count($data) == 1) {
            if ($data[0]["pass"] === md5(md5($pass))) {

                 $loginSession = & $_SESSION["login"];
                 $loginSession["id"] = $data[0]["id"];
            
                 return TRUE;
            } else {
                 return FALSE;
            }
		} else {
			return FALSE;
		}
	}
	
	public function getInfo($loginSession) {
		$data = array();
		

		$sql = "SELECT users.id AS id, users.login AS `login`, users.pass AS pass, p.admin AS admin, g.id AS gid, p.group, g.name AS gname
                FROM users 
                LEFT JOIN users_priv AS p ON (users.id = p.id)
                LEFT JOIN users_subgroup AS g ON (p.group = g.id)
                WHERE users.id = :uid LIMIT 1";
		 
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $loginSession["id"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

		if (count($data) == 1) {
			$data[0]["uid"] = $data[0]["id"];
			$data = $data[0];
		}
	
		if (count($data) > 0) {
			$this->registry->set("auth", TRUE);
			$this->registry->set("ui", $data);
		} else {
			$this->registry->set("auth", FALSE);
			session_destroy();
			
			$this->stopSess($loginSession["id"]);
		}
	}
	
	public function editUser($uid, $login) {
		$sql = "UPDATE users SET `login` = :login WHERE id = :id LIMIT 1";
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":login" => $login);
		$res->execute($param);
	}

	public function editUserPass($uid, $pass) {
		$sql = "UPDATE users SET pass = :pass WHERE id = :id LIMIT 1";
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":pass" => md5(md5($pass)));
		$res->execute($param);
	}
}
?>