<?php
class Model_File extends Engine_Model {
	private $_groups = null;
	private $_users = null;
	private $_desc = null;
	
	// ICO MIME TYPE
	private $_MIME = array(
		array("name" => "img", "ico" => "image.png", "ext" => array("jpg", "jpeg", "gif", "png", "bmp")),
		array("name" => "doc", "ico" => "msword.png", "ext" => array("doc", "docx", "rtf", "oft")),
		array("name" => "pdf", "ico" => "pdf.png", "ext" =>  array("pdf", "djvu")),
		array("name" => "txt", "ico" => "text.png", "ext" =>  array("txt")),
		array("name" => "flv", "ico" => "flash.png", "ext" =>  array("flv")),
		array("name" => "exe", "ico" => "executable.png", "ext" =>  array("exe", "com", "bat")),
		array("name" => "xls", "ico" => "excel.png", "ext" =>  array("xls", "xlsx")),
		array("name" => "mp3", "ico" => "audio.png", "ext" =>  array("mp3", "wav", "flac")),
		array("name" => "html", "ico" => "html.png", "ext" =>  array("html", "htm", "php", "js")),
		array("name" => "zip", "ico" => "compress.png", "ext" =>  array("zip", "rar", "7z", "tar", "bz2", "gz"))
	);

	public function setIcon($ext) {
		$ico = "unknown.png";
		
		for($i=0; $i<count($this->_MIME); $i++) {
			if (in_array($ext, $this->_MIME[$i]["ext"])) $ico = $this->_MIME[$i]["ico"];
		}
		
		return $ico;
	}
	// END ICO MIME TYPE
	
	// SHARE
	public function getMD5FromName($filename) {
		$sql = "SELECT s.md5 AS `md5`
					FROM fm_share AS s
					WHERE s.desc = :filename
					LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":filename" => $filename);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data;
	}
	// END SHARE
	
	// ATTACH
	public function attachFromMD5($md5) {
		$sql = "SELECT f.filename AS `filename`, h.uid, r.right AS `right`
						FROM fm_fs AS f
						LEFT JOIN fm_fs AS f1 ON (f1.filename = f.filename)
						LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
						LEFT JOIN fm_fs_chmod AS r ON (r.fid = f1.id)
						WHERE f.md5 = :md5 AND r.right != 'NULL'
						LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data;
	}
	
	public function attachFromName($filename, $curdir) {
		 $sql = "SELECT f.md5 AS `md5`, h.uid, r.right AS `right`
		FROM fm_fs AS f
		LEFT JOIN fm_fs AS f1 ON (f1.filename = f.filename)
		LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
		LEFT JOIN fm_fs_chmod AS r ON (r.fid = f1.id)
		WHERE f.filename = :filename AND f.pdirid = :pdirid AND f.close = 0
		LIMIT 1";
        
        $res = $this->registry['db']->prepare($sql);
		$param = array(":filename" => $filename, ":pdirid" => $curdir);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
	
		return $data;
	}
	// END ATTACH
	
	// FM AJAX
	function getFileParamsFromMd5($md5) {
		$sql = "SELECT f.id, f.filename, f.size, h.timestamp, MIN(f1.id) AS min_id, MAX(f1.id) AS max_id, f.pdirid
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
		$param = array(":fid" => $data[0]["min_id"]);
		$res->execute($param);
		$right = $res->fetchAll(PDO::FETCH_ASSOC);
	
		if ($right[0]["count"] == 1) {
			$data[0]["right"] = true;
		} else {
			$data[0]["right"] = false;
		}
	
		return $data[0];
	}
	
	function getFileParamsFromName($filename) {
		$fm = & $_SESSION["fm"];
		if (isset($fm["dir"])) {
			$curdir = $fm["dir"];
		} else {
			$curdir = 0;
		}
	
		$sql = "SELECT f.id, f.md5, f.size, h.timestamp, MIN(f1.id) AS min_id, MAX(f1.id) AS max_id, f.pdirid
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
		$param = array(":fid" => $data[0]["min_id"]);
		$res->execute($param);
		$right = $res->fetchAll(PDO::FETCH_ASSOC);
	
		if ($right[0]["count"] == 1) {
			$data[0]["right"] = true;
		} else {
			$data[0]["right"] = false;
		}
	
		return $data[0];
	}
	
	function getRight($curdir) {
		$sql = "SELECT d.uid, r.right AS `right`
		    		FROM fm_dirs AS d
		    		LEFT JOIN fm_dirs_chmod AS r ON (r.did = d.id)
		    		WHERE d.id = :id
		    		LIMIT 1";
			
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $curdir);
		$res->execute($param);
		$right = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $right;
	}
	
	function getAdminDirs($curdir) {
		$sql = "SELECT d.id, d.uid, d.name AS `name`, r.right AS `right`, d.close AS `close`
	    		FROM fm_dirs AS d
	    		LEFT JOIN fm_dirs_chmod AS r ON (r.did = d.id)
	    		WHERE d.pid = :pid";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$dirs = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $dirs;
	}
	
	function getDirs($curdir) {
		$sql = "SELECT d.id, d.uid, d.name AS `name`, r.right AS `right`, d.close AS `close`
	    		FROM fm_dirs AS d
	    		LEFT JOIN fm_dirs_chmod AS r ON (r.did = d.id)
	    		WHERE d.pid = :pid AND d.close = 0";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$dirs = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $dirs;
	}
	
	function getAdminFiles($curdir) {
		$sql = "SELECT DISTINCT(f.id), f.filename AS `name`, f.size, h.uid, h.timestamp, r.right AS `right`, f.close AS `close`, s.desc AS share
				FROM fm_fs AS f
				LEFT JOIN fm_fs AS f1 ON (f1.filename = f.filename)
				LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
				LEFT JOIN fm_fs_chmod AS r ON (r.fid = f1.id)
				LEFT OUTER JOIN fm_share AS s ON (s.md5 = f.md5)
				WHERE f.pdirid = :pid AND r.right != 'NULL'
				AND f.id IN 
				(
					SELECT MAX(id) FROM fm_fs GROUP BY filename ORDER BY id DESC
				)
				GROUP BY f.filename
				ORDER BY f.filename, f.id DESC";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$files = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $files;
	}
	
	function getFiles($curdir) {
		$sql = "SELECT DISTINCT(f.id), f.filename AS `name`, f.size, h.uid, h.timestamp, r.right AS `right`, f.close AS `close`, s.desc AS share
				FROM fm_fs AS f
				LEFT JOIN fm_fs AS f1 ON (f1.filename = f.filename)
				LEFT JOIN fm_fs_history AS h ON (h.fid = f.id)
				LEFT JOIN fm_fs_chmod AS r ON (r.fid = f1.id)
				LEFT OUTER JOIN fm_share AS s ON (s.md5 = f.md5)
				WHERE f.pdirid = :pid AND f.close = 0 AND r.right != 'NULL'
				GROUP BY f.filename
				ORDER BY f.filename";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir);
		$res->execute($param);
		$files = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $files;	
	}
	
	function delfile($fname) {
		$fm = & $_SESSION["fm"];
		$curdir = $fm["dir"];

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
	
		if (($totalSize / 1024) > 1) {
			$totalSize = round($totalSize / 1024, 2) . "&nbsp;Кб";
		} else { $totalSize = round($totalSize, 2) . "&nbsp;Б";
		};
		if (($totalSize / 1024) > 1) {
			$totalSize = round($totalSize / 1024, 2) . "&nbsp;Мб";
		};
	
		return $totalSize;
	}
	
	function getPidFromDir($dirid) {
		$sql = "SELECT `pid` FROM fm_dirs WHERE `id` = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $dirid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $data;
	}
	
	function getNameFromDir($dirid) {
		$sql = "SELECT `name` FROM fm_dirs WHERE `id` = :id LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":id" => $dirid);
		$res->execute($param);
		$dirname = $res->fetchAll(PDO::FETCH_ASSOC);
		
		return $dirname;
	}
	
	function getDirIdFromNameAndPid($dir, $pid) {
		$sql = "SELECT `id` FROM fm_dirs WHERE `name` = :name AND pid = :pid LIMIT 1";

		$res = $this->registry['db']->prepare($sql);
		$param = array(":name" => $dir, "pid" => $pid);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
			
		return $data;
	}
	
	function createDir($curdir, $dirName) {
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
			
			return true;
		} else {
			return false;
		}
	}
	
	function rmDir($curdir, $dirName) {
		$sql = "UPDATE fm_dirs SET close = 1 WHERE `pid` = :pid AND `name` = :name AND close = 0 LIMIT 1";
	
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pid" => $curdir, ":name" => $dirName);
		$res->execute($param);
	}
	
	function moveFiles($curdir, $filename, $buffer) {
		$sql = "UPDATE fm_fs SET pdirid = :dir WHERE `filename` = :filename AND pdirid = :curdir AND close = 0";

		$res = $this->registry['db']->prepare($sql);
		$param = array(":dir" => $curdir, ":filename" => $filename, ":curdir" => $buffer);
		$res->execute($param);
	}
	
	function issetFile($file, $curdir) {
		$sql = "UPDATE fm_fs SET `close` = 1 WHERE `filename` = :filename AND pdirid = :pdirid";
	
		$res = $this->registry['db']->prepare($sql);
		$param = array(":filename" => $file, ":pdirid" => $curdir);
		$res->execute($param);
	}
	
	function getFileHistory($md5, $curdir) {
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
	
		return $data;
	}
	
	function getFileText($md5, $curdir) {
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
	
		return $data;
	}
	
	function addFileText($fid, $text) {
		$sql = "INSERT INTO fm_text SET fid = :fid, uid = :uid, `text` = :text";
		 
		$res = $this->registry['db']->prepare($sql);
		$param = array(":fid" => $fid, ":uid" => $this->registry["ui"]["id"], ":text" => nl2br($text));
		$res->execute($param);
	}
	
	function getFileChmod() {
		$sql = "SELECT ug.id AS pid, ug.name AS pname, usg.id AS sid, usg.name AS sname
	        FROM users_group AS ug
	        LEFT JOIN users_subgroup AS usg ON (usg.pid = ug.id)
	        ORDER BY ug.id";
	
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$this->_groups = $res->fetchAll(PDO::FETCH_ASSOC);
	
		$sql = "SELECT u.id, ug.id AS gid, ug.name AS gname
	        FROM users AS u
	        LEFT JOIN users_priv AS up ON (up.id = u.id)
	        LEFT JOIN users_subgroup AS ug ON (ug.id = up.group)
	        GROUP BY up.id";
	
		$res = $this->registry['db']->prepare($sql);
		$res->execute();
		$this->_users = $res->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function getGroups() {
		return $this->_groups;
	}
	
	function getUsers() {
		return $this->_users;
	}
	

	
	function getUsersChmod($md5, $curdir) {
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
	
		return $data;
	}
	
	function getUsersDirChmod($did) {
		$sql = "SELECT `right`
	        FROM fm_dirs_chmod
	        WHERE did = :did
	        LIMIT 1";
	
		$res = $this->registry['db']->prepare($sql);
		$param = array(":did" => $did);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
	
		return $data;
	}
	
	function addFileRight($fid, $json) {
		$sql = "UPDATE fm_fs_chmod SET `right` = :json WHERE fid = :fid LIMIT 1";
	
		$res = $this->registry['db']->prepare($sql);
		$param = array(":fid" => $fid, ":json" => $json);
		$res->execute($param);
	}
	
	function addDirRight($did, $json) {
		$sql = "UPDATE fm_dirs_chmod SET `right` = :json WHERE did = :did LIMIT 1";
	
		$res = $this->registry['db']->prepare($sql);
		$param = array(":did" => $did, ":json" => $json);
		$res->execute($param);
	}

	function getCurDirName($curdir) {
		$sql = "SELECT `name`
	    	FROM fm_dirs
	    	WHERE id = :pdirid AND close = 0
	    	LIMIT 1";
		 
		$res = $this->registry['db']->prepare($sql);
		$param = array(":pdirid" => $curdir);
		$res->execute($param);
		$data = $res->fetchAll(PDO::FETCH_ASSOC);
		 
		return $data;
	}
	
	function share($md5) {
		$sql = "SELECT COUNT(id) AS count, `desc`
	    	    	FROM fm_share
	    	    	WHERE md5 = :md5
	    	    	LIMIT 1";
	
		$res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5);
		$res->execute($param);
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
	
		if ($row[0]["count"]) {
			$this->_desc = $row[0]["desc"];
	
			return true;
		} else {
			return false;
		}
	}
	
	function getDesc() {
		return $this->_desc;
	}
	
	function addShare($md5, $fname) {
		$sql = "INSERT INTO fm_share (`md5`, `desc`) VALUES (:md5, :desc)";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5, ":desc" => $fname);
		$res->execute($param);
	}
	
	function delShare($md5) {
		$sql = "DELETE FROM `fm_share` WHERE `md5` = :md5 LIMIT 1";
		
		$res = $this->registry['db']->prepare($sql);
		$param = array(":md5" => $md5);
		$res->execute($param);
	}
	// END FM AJAX
	
}
?>