<?php
class Controller_Ajax_Fm extends Engine_Ajax {
    private $count = 0;
    
    private $file = array();
    
    private $tree = null;
    
    private $muser;
    private $mfile;
    
    function __construct() {
    	parent::__construct();
    	
    	$this->muser = new Model_User();
    	$this->mfile = new Model_File();
    }
    
    function save() {
    	$save = new Controller_Ajax_Save();
    	$save->index();
    }

	function getFileParamsFromMd5($md5) {
        $sql = "SELECT f.id, f.filename, f.size, h.timestamp, MIN(f1.id) AS parent_id
        FROM fm_fs AS f
        LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
        LEFT JOIN fm_fs AS f1 ON (f.filename = f1.filename)
        WHERE f.md5 = :md5
        LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$right[0]["count"] = 0;
		
		$sql = "SELECT COUNT(id) AS count FROM fm_fs_chmod WHERE fid = :fid LIMIT 1";
		$res = $this->registry['db']->prepare($sql);
		$param = array(":fid" => $data[0]["parent_id"]);
		$res->execute($param);
		$right = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if ($right[0]["count"] == 1) {
			$data[0]["right"] = true;
		} else {
			$data[0]["right"] = false;
		}
		
		$this->file = $data[0];
	}
	
	function getFileParamsFromName($filename) {
		$fm = & $_SESSION["fm"];
		if (isset($fm["dir"])) {
        	$curdir = $fm["dir"];
        } else {
			$curdir = 0;
		}
        
        $sql = "SELECT f.id, f.md5, f.size, h.timestamp, MIN(f1.id) AS parent_id
        FROM fm_fs AS f
        LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
        LEFT JOIN fm_fs AS f1 ON (f.filename = f1.filename)
        WHERE f.filename = :filename AND f.pdirid = :pdirid AND f.close = 0
        LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":filename" => $filename, ":pdirid" => $curdir);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

		$right[0]["count"] = 0;
		
		$sql = "SELECT COUNT(id) AS count FROM fm_fs_chmod WHERE fid = :fid LIMIT 1";
		$res = $this->registry['db']->prepare($sql);
		$param = array(":fid" => $data[0]["parent_id"]);
		$res->execute($param);
		$right = $res->fetchAll(PDO::FETCH_ASSOC);
		
		if ($right[0]["count"] == 1) {
			$data[0]["right"] = true;
		} else {
			$data[0]["right"] = false;
		}
		
		$this->file = $data[0];
	}
	
	function getFileName($params) {
		$name = rawurldecode($params["name"]);
		$array = $this->getFileParamsFromName($name);
		
		echo $this->file["md5"];
	}
	
	function admin() {
		$fm = & $_SESSION["fm"];
		if ( (isset($fm["admin"])) and ($fm["admin"]) ) {
			$fm["admin"] = false;
		} else {
			$fm["admin"] = true;
		}
		
		
		$this->files();
	}

    function files() {
        $fm = & $_SESSION["fm"];
        if (isset($fm["dir"])) {
        	$curdir = $fm["dir"];
        } else {
			$curdir = 0;
			$fm["dir"] = 0;
		}
		
		if (!isset($fm["admin"])) {
			$fm["admin"] = false;
		}
		
		if ($curdir != 0) {
			$sql = "SELECT d.uid, r.right AS `right`
    		FROM fm_dirs AS d
    		LEFT JOIN fm_dirs_chmod AS r ON (r.did = d.id)
    		WHERE d.id = :id
    		LIMIT 1";
			
			$res = $this->registry['db']->prepare($sql);
			$param = array(":id" => $curdir);
			$res->execute($param);
			$right = $res->fetchAll(PDO::FETCH_ASSOC);
		
			$flag = false;
			if ($this->registry["ui"]["id"] == $right[0]["uid"]) {
				$flag = true;
			}
			if (!$flag) {
				$right = json_decode($right[0]["right"], true);
				foreach($right as $key=>$val) {
					if ($key == "frall") {
						if ($val == "true") {
							$flag = true;
						}
					}
					
					if ($key == "fg" . $this->registry["ui"]["gid"]) {
						if ($val == "true") {
							$flag = true;
						}
					}
					
					if ($key == "user" . $this->registry["ui"]["id"]) {
						if ($val == "true") {
							$flag = true;
						}
					}
				}
			}
			
			if (!$flag) {
				$curdir = 0;
				$fm["dir"] = 0;
			}
		}
        
        $dirs = array(); $files = array();
        $total = 0;
		
        if ( (isset($fm["admin"])) and ($fm["admin"]) ) {
    		$sql = "SELECT d.id, d.uid, d.name AS `name`, r.right AS `right`, d.close AS `close`
    		FROM fm_dirs AS d
    		LEFT JOIN fm_dirs_chmod AS r ON (r.did = d.id)
    		WHERE d.pid = :pid";
        } else {
    		$sql = "SELECT d.id, d.uid, d.name AS `name`, r.right AS `right`, d.close AS `close`
    		FROM fm_dirs AS d
    		LEFT JOIN fm_dirs_chmod AS r ON (r.did = d.id)
    		WHERE d.pid = :pid AND d.close = 0";
        }
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$dirs = $res->fetchAll(PDO::FETCH_ASSOC);
		
		$k = 0; $res_dirs = array();
		for($i=0; $i<count($dirs); $i++) {
			$flag = false;
			if ($this->registry["ui"]["id"] == $dirs[$i]["uid"]) {
				$flag = true;
			}
			if (!$flag) {
				$right = json_decode($dirs[$i]["right"], true);
				foreach($right as $key=>$val) {
					if ($key == "frall") {
						if ($val == "true") {
							$flag = true;
						}
					}
					
					if ($key == "fg" . $this->registry["ui"]["gid"]) {
						if ($val == "true") {
							$flag = true;
						}
					}
					
					if ($key == "user" . $this->registry["ui"]["id"]) {
						if ($val == "true") {
							$flag = true;
						}
					}
				}
			}
			
			if ($flag) {
				$res_dirs[$k] = $dirs[$i];
			}
			
			$k++;
		}

		if ( (isset($fm["admin"])) and ($fm["admin"]) ) {
			$sql = "SELECT DISTINCT(f.id), f.filename AS `name`, f.size, h.uid, h.timestamp, r.right AS `right`, f.close AS `close`
			FROM fm_fs AS f
			LEFT JOIN fm_fs AS f1 ON (f1.filename = f.filename)
			LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
			LEFT JOIN fm_fs_chmod AS r ON (r.fid = f1.id)
			WHERE f.pdirid = :pid AND r.right != 'NULL'
			AND f.id IN 
			(
				SELECT MAX(id) FROM fm_fs GROUP BY filename ORDER BY id DESC
			)
			GROUP BY f.filename
			ORDER BY f.filename, f.id DESC";
		} else {
			$sql = "SELECT DISTINCT(f.id), f.filename AS `name`, f.size, h.uid, h.timestamp, r.right AS `right`, f.close AS `close`
			FROM fm_fs AS f
			LEFT JOIN fm_fs AS f1 ON (f1.filename = f.filename)
			LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
			LEFT JOIN fm_fs_chmod AS r ON (r.fid = f1.id)
			WHERE f.pdirid = :pid AND f.close = 0 AND r.right != 'NULL'
			GROUP BY f.filename
			ORDER BY f.filename";
		}
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$files = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if ($curdir == null) {
        	$shPath = "/";
        } else {
        	$shPath = $fm["dirname"];
        	$up[0] = array("name" => "..");
        	$res_dirs = array_merge($up, $res_dirs);
        }

        $k = 0; $res_files = array();
		for($i=0; $i<count($files); $i++) {
			$flag = false;
			if ($this->registry["ui"]["id"] == $files[$i]["uid"]) {
				$flag = true;
			}
			if (!$flag) {
				$right = json_decode($files[$i]["right"], true);
				foreach($right as $key=>$val) {
					if ($key == "frall") {
						if ($val == "true") {
							$flag = true;
						}
					}
					
					if ($key == "fg" . $this->registry["ui"]["gid"]) {
						if ($val == "true") {
							$flag = true;
						}
					}
					
					if ($key == "user" . $this->registry["ui"]["id"]) {
						if ($val == "true") {
							$flag = true;
						}
					}
				}
			}
			
			if ($flag) {
				$res_files[$k]["close"] = $files[$i]["close"];
				$res_files[$k]["name"] = $files[$i]["name"];
				if (mb_strlen($res_files[$k]["name"]) > 20) {
					$res_files[$k]["shortname"] = mb_substr($res_files[$k]["name"], 0, 16) . ".." . mb_substr($res_files[$k]["name"], mb_strrpos($res_files[$k]["name"], ".")-1, mb_strlen($res_files[$k]["name"])-mb_strrpos($res_files[$k]["name"], ".")+1);
				} else {
					$res_files[$k]["shortname"] = $res_files[$k]["name"];
				}
				
				$ext = mb_substr($res_files[$k]["name"], mb_strrpos($res_files[$k]["name"], ".") + 1);
				$res_files[$k]["ico"] = $this->mfile->setIcon($ext);
				
				$size = $files[$i]["size"];
				$total += $size;
				if (($size / 1024) > 1) { $size = round($size / 1024, 2) . "&nbsp;Кб"; } else { $size = round($size, 2) . "&nbsp;Б"; };
	            if (($size / 1024) > 1) { $size = round($size / 1024, 2) . "&nbsp;Мб"; };
	            $res_files[$k]["size"] = $size;
	            
				$res_files[$k]["date"] = date("H:i d-m-Y",  strtotime($files[$i]["timestamp"]));
				
				$k++;
			}
		}
		
		$buffer = & $_SESSION["clip"];
    	if(isset($buffer["files"])) {
    		$clip = "Скопировано: " . count($buffer["files"]) . " файла(ов)";
    	} else {
    		$clip = "";
    	}
		
		$drop = $this->view->render("fm_attaches", array());
        
        if (($total / 1024) > 1) { $total = round($total / 1024, 2) . "&nbsp;Кб"; } else { $total = round($total, 2) . "&nbsp;Б"; };
    	if (($total / 1024) > 1) { $total = round($total / 1024, 2) . "&nbsp;Мб"; };
        
        echo $this->view->render("fm_content", array("admin" => $fm["admin"], "clip" => $clip, "shPath" => $shPath, "dirs" => $res_dirs, "_thumb" => $this->registry['path']['upload'] . "_thumb/", "files" => $res_files, "totalsize" => $total, "javascript" => $drop));
		
    }
    
	function delfile($params) {
	    $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
		$fname = $params["fname"];

    	$sql = "UPDATE fm_fs SET `close` = '1' WHERE `filename` = :filename AND pdirid = :pdirid";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":filename" => $fname, ":pdirid" => $curdir);
		$res->execute($param);
	}
	
	function getTotalSize() {
		$fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $totalSize = 0;
	
		$sql = "SELECT f.filename, f.size, h.timestamp
		FROM fm_fs AS f
		LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
		WHERE f.pdirid = :pid AND f.close = 0";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$files = $res->fetchAll(PDO::FETCH_ASSOC);

		for($i=0; $i<count($files); $i++) {
			$totalSize += $files[$i]["size"];
		}
		
		if (($totalSize / 1024) > 1) { $totalSize = round($totalSize / 1024, 2) . "&nbsp;Кб"; } else { $totalSize = round($totalSize, 2) . "&nbsp;Б"; };
		if (($totalSize / 1024) > 1) { $totalSize = round($totalSize / 1024, 2) . "&nbsp;Мб"; };
		
		echo $totalSize;
	}
    
    function chdir($params) {
        $dir = $params["dir"];
        
        $fm = & $_SESSION["fm"];
                
        if ($dir == "..") {
        	$sql = "SELECT `pid` FROM fm_dirs WHERE `id` = :id LIMIT 1";
        
        	$res = $this->registry['db']->prepare($sql);
			$param = array(":id" => $fm["dir"]);
			$res->execute($param);
			$data = $res->fetchAll(PDO::FETCH_ASSOC);

			if (count($data) == 0) {
				unset($fm["dir"]);
				$fm["dirname"] = "/";
			} else {
				$sql = "SELECT `name` FROM fm_dirs WHERE `id` = :id LIMIT 1";
        
	        	$res = $this->registry['db']->prepare($sql);
				$param = array(":id" => $data[0]["pid"]);
				$res->execute($param);
				$dirname = $res->fetchAll(PDO::FETCH_ASSOC);
			
				if (count($dirname) == 0) {
					$fm["dir"] = 0;
					$fm["dirname"] = "/";
				} else {
					$fm["dir"] = $data[0]["pid"];
					$fm["dirname"] = $dirname[0]["name"];
				}
			}
        } else {
        	$sql = "SELECT `id` FROM fm_dirs WHERE `name` = :name AND pid = :pid LIMIT 1";
        
        	$res = $this->registry['db']->prepare($sql);
			$param = array(":name" => $dir, "pid" => $fm["dir"]);
			$res->execute($param);
			$data = $res->fetchAll(PDO::FETCH_ASSOC);
			
            $fm["dir"] = $data[0]["id"];
            $fm["dirname"] = $dir;
        }
        
        $this->files();
    }
    
    function createDir($params) {
        $dirName = $params["dirName"];
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $sql = "SELECT count(id) AS count FROM fm_dirs WHERE `name` = :name AND `pid` = :pid LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
        $param = array(":pid" => $curdir, ":name" => $dirName);
        $res->execute($param);
        $data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        if ($data[0]["count"] == 0) {        
	        $sql = "INSERT INTO fm_dirs (uid, `pid`, `name`) VALUES (:uid, :pid, :name)";
	        
	        $res = $this->registry['db']->prepare($sql);
			$param = array(":uid" => $this->registry["ui"]["id"], ":pid" => $curdir, ":name" => $dirName);
			$res->execute($param);
			
			$did = $this->registry['db']->lastInsertId();
			
			$sql = "INSERT INTO fm_dirs_chmod (did, `right`) VALUES (:did, :json)";
	        
	        $res = $this->registry['db']->prepare($sql);
			$param = array(":did" => $did, ":json" => '{"frall":"true"}');
			$res->execute($param);
			
			$this->files();
        } else {
        	echo "error";
        }
    }
    
    function rmDir($params) {
        $dirName = $params["dirName"];
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $sql = "UPDATE fm_dirs SET close = 1 WHERE `pid` = :pid AND `name` = :name AND close = 0 LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir, ":name" => $dirName);
		$res->execute($param);

        $this->files();
    }
    
    function copyFiles($params) {
    	$fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];

        if (isset($params["file"])) {
	    	$buffer = & $_SESSION["clip"];
	    	$buffer["dir"] = $curdir;
	    	$buffer["files"] = $params["file"];
	    	
	    	echo "Скопировано: " . count($buffer["files"]) . " файла(ов)";
        }
    }
    
    function moveFiles() {
    	$fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
    	
    	$buffer = & $_SESSION["clip"];
        
    	if ( (isset($buffer["dir"])) and ($buffer["files"]) ) {
	        foreach($buffer["files"] as $part) {
		        $sql = "UPDATE fm_fs SET pdirid = :dir WHERE `filename` = :filename AND pdirid = :curdir AND close = 0";
		        
		        $res = $this->registry['db']->prepare($sql);
				$param = array(":dir" => $curdir, ":filename" => $part, ":curdir" => $buffer["dir"]);
				$res->execute($param);
	        }
    	}
        
    	unset($_SESSION["clip"]);
    	
        $this->files();
    }
    
    function issetFile($params) {
    	$file = urldecode($params["file"]);
    	
    	$fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];

		$sql = "UPDATE fm_fs SET `close` = 1 WHERE `filename` = :filename AND pdirid = :pdirid";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":filename" => $file, ":pdirid" => $curdir);
		$res->execute($param);
    }

    function getFileHistory($params) {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];
    	
    	$md5 = $params["md5"];
    	
        $sql = "SELECT h.timestamp AS `timestamp`, h.uid, fs1.md5
        FROM fm_fs AS fs
        LEFT JOIN fm_fs AS fs1 ON (fs.filename = fs1.filename)
        LEFT JOIN fm_fs_history AS h ON (h.fid = fs1.id)
        WHERE fs.md5 = :md5 AND fs.pdirid = :pdirid AND fs1.pdirid = :pdirid
        ORDER BY h.timestamp DESC";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5, ":pdirid" => $curdir);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($data as $part) {
			if ($part["uid"] != null) {
				$uid = $this->muser->getUserInfo($part["uid"]);
				echo $this->view->render("fm_fhistory", array("md5" => $part["md5"], "date" => $part["timestamp"], "uid" => $uid));
			}
		}
    }
    
    function getFileText($params) {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];
    	
    	$md5 = $params["md5"];
    	
    	$sql = "SELECT t.uid, t.text AS `text`, t.timestamp AS `timestamp`
    	FROM fm_fs AS fs
        LEFT JOIN fm_fs AS fs1 ON (fs.filename = fs1.filename)
        LEFT JOIN fm_text AS t ON (t.fid = fs1.id)
        WHERE fs.md5 = :md5 AND fs.pdirid = :pdirid AND fs1.pdirid = :pdirid
    	ORDER BY timestamp DESC";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5, ":pdirid" => $curdir);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		foreach($data as $part) {
			if ($part["uid"] != null) {
				$uid = $this->muser->getUserInfo($part["uid"]);
				echo $this->view->render("fm_ftext", array("text" => $part["text"], "date" => $part["timestamp"], "uid" => $uid));
			}
		}
    }
    
    function addFileText($params) {
    	$text = $params["text"];
    	$md5 = $params["md5"];
    	
    	$this->getFileParamsFromMd5($md5);
    	$fid = $this->file["id"]; 
    	
    	$sql = "INSERT INTO fm_text SET fid = :fid, uid = :uid, `text` = :text";
	        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":fid" => $fid, ":uid" => $this->registry["ui"]["id"], ":text" => nl2br($text));
		$res->execute($param);
    	
    	echo $this->view->render("fm_ftext", array("text" => nl2br($text), "date" => date("Y-m-d H:i:s")));
    }
    
    function getFileChmod($params) {
    	$md5 = $params["md5"];
    	
    	$sql = "SELECT ug.id AS pid, ug.name AS pname, usg.id AS sid, usg.name AS sname
        FROM users_group AS ug
        LEFT JOIN users_subgroup AS usg ON (usg.pid = ug.id)
        ORDER BY ug.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$groups = $res->fetchAll(PDO::FETCH_ASSOC);

		$users = array();
        
        $sql = "SELECT u.id, ug.id AS gid, ug.name AS gname
        FROM users AS u
        LEFT JOIN users_priv AS up ON (up.id = u.id)
        LEFT JOIN users_subgroup AS ug ON (ug.id = up.group)
        GROUP BY up.id";
        
        $res = $this->registry['db']->prepare($sql);
        $res->execute();
		$users = $res->fetchAll(PDO::FETCH_ASSOC);

		$sortlist = array();
		foreach($groups as $group) {
			foreach($users as $user) {
				if ( ($user["gname"] == $group["sname"]) and ($group["sname"] != null) ) {
					$udata = $this->view->render("fm_data", array("data" => $this->muser->getUserInfo($user["id"])));
					$sortlist[$group["pname"]][$group["sid"]][] = $udata;
				}
			}
		}

		$this->print_array($sortlist);

		echo $this->view->render("fm_tree", array("group" => $groups, "list" => $this->tree, "md5" => $md5));
    }
    
	private function print_array($arr) {
		if (!is_array($arr)) {
			return;
		}

		while(list($key, $val) = each($arr)) {
			if (!is_array($val)) {
				if ($val == null) {
					$val = "пусто";
				}

				$this->tree .= "<ul><li><div style='margin: 0 0 0 10px'>" . $val . "</div></li></ul>";
			}
			if (is_array($val)) {
				if ($key != "0") {
					if(is_numeric($key)) {
						$gid = $this->getSubgroupName($key);
						$this->tree .= "<ul><li><span class='folder'><label><input type='checkbox' id='fg" . $key . "' name='fgruser[]' class='fgruser' value='" . $key . "' />&nbsp;" . $gid . "</label></span>";
					} else {
						$this->tree .= "<ul><li><span class='folder'>&nbsp;" . $key . "</span>";
					}
				}

				$this->print_array($val);

				if ($key != "0") {
					$this->tree .= "</li></ul>";
				}
			}
		}
	}
	
    function getGroupId($gname) {
		$sql = "SELECT id 
        FROM users_group
        WHERE `name` = :gname
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":gname" => $gname);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["id"];
    }
    
	public function getSubgroupName($sid) {
		$sql = "SELECT `name` 
        FROM users_subgroup
        WHERE id = :sid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":sid" => $sid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
        
        return $data[0]["name"];
    }
    
    function getUsersChmod($params) {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];
    	
    	$md5 = $params["md5"];
    	
    	$data = array();
    	
    	$sql = "SELECT r.right AS `right` 
        FROM fm_fs AS fs
        LEFT JOIN fm_fs AS fs1 ON (fs1.filename =fs.filename)
        LEFT JOIN fm_fs_chmod AS r ON (r.fid = fs1.id)
        WHERE fs.md5 = :md5 AND fs.pdirid = :pdirid AND fs1.pdirid = :pdirid
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5, ":pdirid" => $curdir);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        echo $data[0]["right"];
    }
    
    function getUsersDirChmod($params) {
    	$did = $params["did"];
    	
    	$data = array();
    	
    	$sql = "SELECT `right` 
        FROM fm_dirs_chmod
        WHERE did = :did
        LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":did" => $did);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);

        echo $data[0]["right"];
    }
    
    function addFileRight($params) {
    	$json = $params["json"];
    	$md5 = $params["md5"];
    	
    	$this->getFileParamsFromMd5($md5);

    	$sql = "UPDATE fm_fs_chmod SET `right` = :json WHERE fid = :fid LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":fid" => $this->file["parent_id"], ":json" => $json);
		$res->execute($param);
    }
    
    function addDirRight($params) {
    	$json = $params["json"];
    	$did = $params["did"];

    	$sql = "UPDATE fm_dirs_chmod SET `right` = :json WHERE did = :did LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":did" => $did, ":json" => $json);
		$res->execute($param);
    }
    
    function shDirRight($params) {
    	$did = $params["did"];
    	
    	$sql = "SELECT ug.id AS pid, ug.name AS pname, usg.id AS sid, usg.name AS sname
        FROM users_group AS ug
        LEFT JOIN users_subgroup AS usg ON (usg.pid = ug.id)
        ORDER BY ug.id";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array();
		$res->execute($param);
		$groups = $res->fetchAll(PDO::FETCH_ASSOC);

		$users = array();
        
        $sql = "SELECT u.id, ug.id AS gid, ug.name AS gname
        FROM users AS u
        LEFT JOIN users_priv AS up ON (up.id = u.id)
        LEFT JOIN users_subgroup AS ug ON (ug.id = up.group)
        GROUP BY up.id";
        
        $res = $this->registry['db']->prepare($sql);
        $res->execute();
		$users = $res->fetchAll(PDO::FETCH_ASSOC);

		$sortlist = array();
		foreach($groups as $group) {
			foreach($users as $user) {
				if ( ($user["gname"] == $group["sname"]) and ($group["sname"] != null) ) {
					$udata = $this->view->render("fm_data", array("data" => $this->muser->getUserInfo($user["id"])));
					$sortlist[$group["pname"]][$group["sid"]][] = $udata;
				}
			}
		}

		$this->print_array($sortlist);

		echo $this->view->render("fm_dtree", array("group" => $groups, "list" => $this->tree, "did" => $did));
    }
    
    function getCurDirName() {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];

    	$sql = "SELECT `name`
    	FROM fm_dirs
    	WHERE id = :pdirid AND close = 0
    	LIMIT 1";
    	
    	$res = $this->registry['db']->prepare($sql);
    	$param = array(":pdirid" => $curdir);
    	$res->execute($param);
    	$data = $res->fetchAll(PDO::FETCH_ASSOC);
    	
    	if (isset($data[0]["name"])) {
    		echo $data[0]["name"] . "/";
    	} else {
    		echo null;
    	}
    }
}
?>