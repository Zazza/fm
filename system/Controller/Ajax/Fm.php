<?php
class Controller_Ajax_Fm extends Engine_Ajax {
    private $_file = array();
    private $_tree = null;
    private $_muser;
    private $_mfile;
    private $_desc = null;
    
    function __construct() {
    	parent::__construct();
    	
    	$this->_muser = new Model_User();
    	$this->_mfile = new Model_File();
    }
    
    function save() {
    	$save = new Controller_Ajax_Save();
    	$save->index();
    }

	function getFileParamsFromMd5($md5) {
		$this->_file = $this->_mfile->getFileParamsFromMd5($md5);
	}
	
	function getFileParamsFromName($filename) {
		$this->_file = $this->_mfile->getFileParamsFromName($filename);
	}
	
	function getFileName($params) {
		$name = rawurldecode($params["name"]);
		$array = $this->getFileParamsFromName($name);
		
		echo $this->_file["md5"];
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
			$right = $this->_mfile->getRight($curdir);
		
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
    		$dirs = $this->_mfile->getAdminDirs($curdir);
        } else {
    		$dirs = $this->_mfile->getDirs($curdir);
        }
		
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
			$files = $this->_mfile->getAdminFiles($curdir);
		} else {
			$files = $this->_mfile->getFiles($curdir);
		}
        
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
				$res_files[$k]["id"] = $files[$i]["id"];
				$res_files[$k]["close"] = $files[$i]["close"];
				$res_files[$k]["name"] = $files[$i]["name"];
				if (mb_strlen($res_files[$k]["name"]) > 20) {
					$res_files[$k]["shortname"] = mb_substr($res_files[$k]["name"], 0, 16) . ".." . mb_substr($res_files[$k]["name"], mb_strrpos($res_files[$k]["name"], ".")-1, mb_strlen($res_files[$k]["name"])-mb_strrpos($res_files[$k]["name"], ".")+1);
				} else {
					$res_files[$k]["shortname"] = $res_files[$k]["name"];
				}
				
				$ext = mb_substr($res_files[$k]["name"], mb_strrpos($res_files[$k]["name"], ".") + 1);
				$res_files[$k]["ico"] = $this->_mfile->setIcon($ext);
				
				$res_files[$k]["share"] = $files[$i]["share"];
				
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
        
        if (($total / 1024) > 1) { $total = round($total / 1024, 2) . "&nbsp;Кб"; } else { $total = round($total, 2) . "&nbsp;Б"; };
    	if (($total / 1024) > 1) { $total = round($total / 1024, 2) . "&nbsp;Мб"; };
        
        echo $this->view->render("fm_content", array("admin" => $fm["admin"], "clip" => $clip, "shPath" => $shPath, "dirs" => $res_dirs, "_thumb" => $this->registry['path']['upload'] . "_thumb/", "files" => $res_files, "totalsize" => $total));
		
    }
    
	function delfile($params) {
		$fname = $params["fname"];
		$this->_mfile->delfile($fname);
	}
	
	function getTotalSize() {
		echo $this->_mfile->getTotalSize();
	}
    
    function chdir($params) {
        $dir = $params["dir"];
        
        $fm = & $_SESSION["fm"];
                
        if ($dir == "..") {
        	$data = $this->_mfile->getPidFromDir($fm["dir"]);

			if (count($data) == 0) {
				unset($fm["dir"]);
				$fm["dirname"] = "/";
			} else {
				$dirname = $this->_mfile->getNameFromDir($data[0]["pid"]);

				if (count($dirname) == 0) {
					$fm["dir"] = 0;
					$fm["dirname"] = "/";
				} else {
					$fm["dir"] = $data[0]["pid"];
					$fm["dirname"] = $dirname[0]["name"];
				}
			}
        } else {
        	$data = $this->_mfile->getDirIdFromNameAndPid($dir, $fm["dir"]);
			
            $fm["dir"] = $data[0]["id"];
            $fm["dirname"] = $dir;
        }
        
        $this->files();
    }
    
    function createDir($params) {
        $dirName = $params["dirName"];
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $flag = $this->_mfile->createDir($curdir, $dirName);
        
        if ($flag) {
        	$this->files();
        } else {
        	echo "error";
        }
    }
    
    function rmDir($params) {
        $dirName = $params["dirName"];
        
        $fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $this->_mfile->rmDir($curdir, $dirName);

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
	        	$this->_mfile->moveFiles($curdir, $part, $buffer["dir"]);
	        }
    	}
        
    	unset($_SESSION["clip"]);
    	
        $this->files();
    }
    
    function issetFile($params) {
    	$file = urldecode($params["file"]);
    	
    	$fm = & $_SESSION["fm"];
        $curdir = $fm["dir"];
        
        $this->_mfile->issetFile($file, $curdir);
    }

    function getFileHistory($params) {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];
    	
    	$md5 = $params["md5"];
    	
    	$data = $this->_mfile->getFileHistory($md5, $curdir);
		
		foreach($data as $part) {
			if ($part["uid"] != null) {
				$uid = $this->_muser->getUserInfo($part["uid"]);
				echo $this->view->render("fm_fhistory", array("md5" => $part["md5"], "date" => $part["timestamp"], "uid" => $uid));
			}
		}
    }
    
    function getFileText($params) {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];
    	
    	$md5 = $params["md5"];
    	
    	$data = $this->_mfile->getFileText($md5, $curdir);
		
		foreach($data as $part) {
			if ($part["uid"] != null) {
				$uid = $this->_muser->getUserInfo($part["uid"]);
				echo $this->view->render("fm_ftext", array("text" => $part["text"], "date" => $part["timestamp"], "uid" => $uid));
			}
		}
    }
    
    function addFileText($params) {
    	$text = $params["text"];
    	$md5 = $params["md5"];
    	
    	$this->getFileParamsFromMd5($md5);
    	$fid = $this->_file["id"]; 
    	
    	$this->_mfile->addFileText($fid, $text);
    	
    	echo $this->view->render("fm_ftext", array("text" => nl2br($text), "date" => date("Y-m-d H:i:s")));
    }
    
    function getFileChmod($params) {
    	$md5 = $params["md5"];
    	
    	$this->_mfile->getFileChmod();
    	
    	$groups = $this->_mfile->getGroups();
    	$users = $this->_mfile->getUsers();

		$sortlist = array();
		foreach($groups as $group) {
			foreach($users as $user) {
				if ( ($user["gname"] == $group["sname"]) and ($group["sname"] != null) ) {
					$udata = $this->view->render("fm_data", array("data" => $this->_muser->getUserInfo($user["id"])));
					$sortlist[$group["pname"]][$group["sid"]][] = $udata;
				}
			}
		}

		$this->print_array($sortlist);

		echo $this->view->render("fm_tree", array("group" => $groups, "list" => $this->_tree, "md5" => $md5));
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

				$this->_tree .= "<ul><li><div style='margin: 0 0 0 10px'>" . $val . "</div></li></ul>";
			}
			if (is_array($val)) {
				if ($key != "0") {
					if(is_numeric($key)) {
						$gid = $this->_muser->getSubgroupName($key);
						$this->_tree .= "<ul><li><span class='folder'><label><input type='checkbox' id='fg" . $key . "' name='fgruser[]' class='fgruser' value='" . $key . "' />&nbsp;" . $gid . "</label></span>";
					} else {
						$this->_tree .= "<ul><li><span class='folder'>&nbsp;" . $key . "</span>";
					}
				}

				$this->print_array($val);

				if ($key != "0") {
					$this->_tree .= "</li></ul>";
				}
			}
		}
	}
    
    function getUsersChmod($params) {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];
    	
    	$md5 = $params["md5"];
    	
    	$data = $this->_mfile->getUsersChmod($md5, $curdir);

        echo $data[0]["right"];
    }
    
    function getUsersDirChmod($params) {
    	$did = $params["did"];
    	
		$data = $this->_mfile->getUsersDirChmod($did);

        echo $data[0]["right"];
    }
    
    function addFileRight($params) {
    	$json = $params["json"];
    	$md5 = $params["md5"];
    	
    	$this->getFileParamsFromMd5($md5);
    	
    	$this->_mfile->addFileRight($this->_file["min_id"], $json);
    }
    
    function addDirRight($params) {
    	$json = $params["json"];
    	$did = $params["did"];
    	
    	$this->_mfile->addDirRight($did, $json);
    }
    
    function shDirRight($params) {
    	$did = $params["did"];
    	
    	$this->_mfile->getFileChmod();
    	
    	$groups = $this->_mfile->getGroups();
    	$users = $this->_mfile->getUsers();

		$sortlist = array();
		foreach($groups as $group) {
			foreach($users as $user) {
				if ( ($user["gname"] == $group["sname"]) and ($group["sname"] != null) ) {
					$udata = $this->view->render("fm_data", array("data" => $this->_muser->getUserInfo($user["id"])));
					$sortlist[$group["pname"]][$group["sid"]][] = $udata;
				}
			}
		}

		$this->print_array($sortlist);

		echo $this->view->render("fm_dtree", array("group" => $groups, "list" => $this->_tree, "did" => $did));
    }
    
    function getCurDirName() {
    	$fm = & $_SESSION["fm"];
    	$curdir = $fm["dir"];

    	$data = $this->_mfile->getCurDirName($curdir);
    	
    	if (isset($data[0]["name"])) {
    		echo $data[0]["name"] . "/";
    	} else {
    		echo null;
    	}
    }

	function share($params) {
    	$md5 = $params["md5"];
    	
    	if ($this->_mfile->share($md5)) {
    		$this->_mfile->delShare($md5);
    		
    		$this->getFileParamsFromMd5($md5);
    		$row["fid"] = $this->_file["max_id"];
    		$row["action"] = "unshare";
    	} else {
    		$this->getFileParamsFromMd5($md5);
    		
    		if ($this->_file["pdirid"] == 0) {
    			$fname = $this->_file["filename"];
    		} else {
    			$fname = "(" . $this->_file["pdirid"] . ")" . $this->_file["filename"];
    		}

    		$this->_mfile->addShare($md5, $fname);
    		
    		$row["fid"] = $this->_file["max_id"];
    		$row["desc"] = $fname;
    		$row["action"] = "share";
    	}
    	
    	echo json_encode($row);
    }
    
    function getShare($params) {
    	$md5 = $params["md5"];
    	
    	if ($this->_mfile->share($md5)) {
    		echo $this->_mfile->getDesc();
    	}
    }
}
?>