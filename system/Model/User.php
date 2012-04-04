<?php
class Model_User extends Engine_Model {
	public $tid;
	private $online_time = 300;
    
    public function getUserInfo($uid) {
    	$data = array();
    
    	$sql = "SELECT users.id AS id, users.login AS `login`, users.pass, users.quota,  p.admin AS admin, g.id AS gid, p.group, g.name AS gname
                FROM fm_users AS users 
                LEFT JOIN fm_users_priv AS p ON (users.id = p.id)
                LEFT JOIN fm_users_subgroup AS g ON (p.group = g.id)
                WHERE users.id = :uid LIMIT 1";
    		 
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":uid" => $uid);
    	$res->execute($param);
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    
    	if (count($data) == 1) {
    		$data[0]["uid"] = $data[0]["id"];
    		$data = $data[0];
    	}
    
    	return $data;
    }

    public function getGidFromUid($uid) {
		$sql = "SELECT up.group AS `group`
        FROM fm_users_priv AS up
        WHERE up.id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
		if (count($data) > 0) {
        	return $data[0]["group"];
		}
    }
    
    public function getUserId($login) {
		$sql = "SELECT id 
        FROM fm_users
        WHERE login = :login
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
		if ( (isset($data[0]["id"])) and (is_numeric($data[0]["id"])) ) {
        	return $data[0]["id"];
		}
    }
    
    public function getUserInfoFromGroup($gid) {
        $data = array();
                    
		$sql = "SELECT users.id AS uid, users.login AS login, users.pass AS pass, p.admin AS admin, g.id AS gid, g.name AS gname 
        FROM fm_users AS users
        LEFT JOIN fm_users_priv AS p ON (users.id = p.id)
        LEFT JOIN fm_users_subgroup AS g ON (p.group = g.id)
        WHERE g.id = :gid";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function addUser($login, $pass, $val) {
        $sql = "INSERT INTO fm_users (login, pass, quota) VALUES (:login, :pass, :quota)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login, ":pass" => md5(md5($pass)), ":quota" => $val);
		$res->execute($param);

		$uid = $this->registry['db']->lastInsertId();
        
        return $uid;
    }
    
    public function addUserPriv($uid, $priv, $gname) {
        if ($priv == "admin") {
            $admin = 1;
        } else {
            $admin = 0;
        }
        
        $sql = "INSERT INTO fm_users_priv (id, admin, `group`) VALUES (:id, :admin, :group)";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":group" => $gname);
		$res->execute($param);
    }
    
    public function editUser($uid, $login, $val) {
        $sql = "UPDATE fm_users SET `login` = :login, quota = :quota WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":login" => $login, ":quota" => $val);
		$res->execute($param);
    }
    
    public function editUserPass($uid, $pass) {
        $sql = "UPDATE fm_users SET pass = :pass WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":pass" => md5(md5($pass)));
		$res->execute($param);
    }
    
    public function editUserPriv($uid, $priv, $gname) {
        if ($priv == "admin") {
            $admin = 1;
        } else {
            $admin = 0;
        }
        
        $sql = "UPDATE fm_users_priv SET id = :id, admin = :admin, `group` = :group WHERE id = :id LIMIT 1";
        $res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $uid, ":admin" => $admin, ":group" => $gname);
		$res->execute($param);
    }
    
    public function getUsersList() {
		$sql = "SELECT users.id AS id, users.login AS login, p.admin AS admin, p.group AS gid, g.name AS gname
        FROM fm_users AS users
        LEFT JOIN fm_users_priv AS p ON (users.id = p.id)
        LEFT JOIN fm_users_group AS g ON (p.group = g.id)
        ORDER BY users.id";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
	public function issetLogin($login) {
		$sql = "SELECT COUNT(id) AS count FROM fm_users WHERE login = :login";

		$res = $this->registry['db']->prepare($sql);
		$param = array(":login" => $login);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);

		if (count($row) > 0) $count = $row[0]["count"];

		if ($count > 0) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

    public function delUser($uid) {
		$sql = "DELETE FROM fm_users WHERE id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
        
		$sql = "DELETE FROM fm_users_priv WHERE id = :uid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":uid" => $uid);
		$res->execute($param);
    }
    
    public function getGroups() {
		$sql = "SELECT ug.id AS pid, ug.name AS pname, usg.id AS sid, usg.name AS sname
        FROM fm_users_group AS ug
        LEFT JOIN fm_users_subgroup AS usg ON (usg.pid = ug.id)
        ORDER BY ug.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
    
    public function getUniqGroups() {
		$sql = "SELECT ug.id AS pid, ug.name AS pname
        FROM fm_users_group AS ug
        ORDER BY ug.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }

    public function getGroupName($gid) {
		$sql = "SELECT `name` 
        FROM fm_users_group
        WHERE id = :gid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
		if (count($data) > 0) {
        	return $data[0]["name"];
		}
    }
    
    public function getSubgroupName($sid) {
		$sql = "SELECT `name` 
        FROM fm_users_subgroup
        WHERE id = :sid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":sid" => $sid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    public function getSubgroups($pid) {
		$sql = "SELECT id, `name` 
        FROM fm_users_subgroup
        WHERE pid = :pid
        ORDER BY id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $pid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function addSubgroup($pid, $name) {
    	$sql = "INSERT INTO fm_users_subgroup (pid, name) VALUES (:pid, :name)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $pid, ":name" => $name);
		$res->execute($param);
    }
    
	public function delSubgroup($id) {
		$sql = "DELETE FROM fm_users_subgroup WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id);
		$res->execute($param);
    }
    
	public function editCat($id, $name) {
		$sql = "UPDATE fm_users_subgroup SET name = :name WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $id, ":name" => $name);
		$res->execute($param);
    }
    
    public function getCatName($id) {
        $data = array();
        
		$sql = "SELECT id, name
        FROM fm_users_subgroup
        WHERE id = :id
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute(array(":id" => $id));
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0];
    }
    
    public function getUsersGroups() {
        $data = array();
        
        $sql = "SELECT u.id, ug.id AS gid, ug.name AS gname
        FROM fm_users AS u
        LEFT JOIN fm_users_priv AS up ON (up.id = u.id)
        LEFT JOIN fm_users_subgroup AS ug ON (ug.id = up.group)
        GROUP BY up.id";
        
        $res = $this->registry['db']->prepare($sql);
        $res->execute();
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data;
    }
    
    public function getGroupId($gname) {
		$sql = "SELECT id 
        FROM fm_users_group
        WHERE `name` = :gname
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gname" => $gname);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["id"];
    }
    
    public function getSubgroupId($subgname) {
    	$sql = "SELECT id
            FROM fm_users_subgroup
            WHERE `name` = :gname
            LIMIT 1";
    
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":gname" => $subgname);
    	$res->execute($param);
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    
    	if (count($data) > 0) {
	    	return $data[0]["id"];
    	}
    }
    
    public function addGroups($gname) {
        if ($gname == "") {
            return FALSE;
        }
        
		$sql = "SELECT id
        FROM fm_users_group
        WHERE `name` = :name
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => htmlspecialchars($gname));
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $flag = FALSE;
        
        if (!isset($data[0]["id"])) {
            $flag = TRUE;
        }
        
        if ($flag) {
    		$sql = "INSERT INTO fm_users_group (`name`) VALUES (:name)";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":name" => htmlspecialchars($gname));
    		$res->execute($param);
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function editGroupName($gid, $gname) {
        if ($gname == "") {
            return FALSE;
        }
        
		$sql = "SELECT id
        FROM fm_users_group
        WHERE `name` = :name
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => htmlspecialchars($gname));
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        $flag = FALSE;
        
        if (!isset($data[0]["id"])) {
            $flag = TRUE;
        } elseif ($gid == $data[0]["id"]) {
            $flag = TRUE;
        }
        
        if ($flag) {
    		$sql = "UPDATE fm_users_group SET `name` = :gname WHERE id = :gid LIMIT 1";
    		
    		$res = $this->registry['db']->prepare($sql);
    		$param = array(":gid" => $gid, ":gname" => htmlspecialchars($gname));
    		$res->execute($param);
            
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function delGroup($gid) {
		$sql = "DELETE FROM fm_users_group WHERE id = :gid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gid" => $gid);
		$res->execute($param);
    }

    // список пользователей и групп для формы создания или правки задачи
	function getUsers() {
		$k=0;
		$gdata = $this->getGroups();
		$udata = $this->getUsersList();
		for($i=0; $i<count($gdata); $i++) {
			$data[$k]["id"] = $gdata[$i]["sid"];
			$data[$k]["type"] = "g";
			$data[$k]["desc"] = $gdata[$i]["sname"];
	
			foreach($udata as $part) {
				if ($part["gid"] == $gdata[$i]["sid"]) {
	
					$k++;
	
					$data[$k]["id"] = $part["id"];
					$data[$k]["type"] = "u";
					$data[$k]["desc"] = $part["name"] . " " . $part["soname"];
				}
			}
	
			$k++;
		}
	
		$data[$k]["type"] = "all";
		$data[$k]["id"] = 0;
		$data[$k]["desc"] = "все";
		
		return $data; 
	}
	
	function getUniqUsers($post) {
		$uniq = array();
		
		$users = array();
		if ($post["rall"] == "1") {
			$group_users = $this->getUsersList();
			foreach($group_users as $user) {
				$users[] = $user["id"];
			}
		} else {
			foreach($post["gruser"] as $part) {
				$group_users = $this->getUserInfoFromGroup($part);
				foreach($group_users as $user) {
					$users[] = $user["uid"];
				}
			}
			
			foreach($post["ruser"] as $part) {
				$users[] = $part;
			}
		}

		for($i=0; $i<count($users); $i++) {
			$flag = true; 
			foreach($uniq as $part) {
				if ($part == $users[$i]) {
					$flag = false;
				}
			}
			
			if ($flag) {
				$uniq[] = $users[$i];
			}
		}
		
		return $uniq;
	}
	
	function getNowSize() {
		$sql = "SELECT SUM(f.size) AS sum
		FROM fm_fs AS f
		LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
		WHERE h.uid = :id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $this->registry["ui"]["id"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data[0]["sum"];
	}
	
	function getUserQuota() {
		$sql = "SELECT quota FROM fm_users WHERE id = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $this->registry["ui"]["id"]);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data[0]["quota"];
	}
	
	function getTotal() {
		$sql = "SELECT SUM(size) AS sum FROM fm_fs";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$data["all"] = $row[0]["sum"];
		
		$sql = "SELECT SUM(f.size) AS sum, u.login AS `login`, u.quota AS `quota`
		FROM fm_fs AS f
		LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
		LEFT JOIN fm_users AS u ON (u.id = h.uid)
		GROUP BY h.uid
		ORDER BY sum DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$data["users"] = $row;
		
		return $data;
	}
}
?>
