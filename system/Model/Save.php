<?php
class Model_Save extends Engine_Model {
	private $filename = null;
	public $md5 = null;

	function save() {
		if ($this->registry["auth"]) {
			$fm = & $_SESSION["fm"];
			$curdir = $fm["dir"];
			 
			$input = fopen("php://input", "r");
			$temp = tmpfile();
			$realSize = stream_copy_to_stream($input, $temp);
			fclose($input);
			 
			if ($realSize != $this->getSize()){
				return false;
			};

			$sql = "SELECT COUNT(id) AS count FROM fm_fs WHERE `filename` = :filename AND pdirid = :pdirid LIMIT 1";

			$res = $this->registry['db']->prepare($sql);
			$param = array(":filename" => $this->filename, ":pdirid" => $curdir);
			$res->execute($param);
			$isset = $res->fetchAll(PDO::FETCH_ASSOC);

			$sql = "INSERT INTO fm_fs (`md5`, `filename`, `pdirid`, `size`) VALUES (:md5, :filename, :curdir, :size)";
			 
			$res = $this->registry['db']->prepare($sql);
			$param = array(":md5" => $this->md5, ":filename" => $this->filename, ":curdir" => $curdir, ":size" => $realSize);
			$res->execute($param);
				
			$fid = $this->registry['db']->lastInsertId();
				
			if ($isset[0]["count"] == 0) {
				$sql = "INSERT INTO fm_fs_chmod (fid, `right`) VALUES (:fid, :json)";

				$res = $this->registry['db']->prepare($sql);
				$param = array(":fid" => $fid, ":json" => '{"frall":"true"}');
				$res->execute($param);
			}

			$sql = "INSERT INTO fm_fs_history (fid, uid) VALUES (:fid, :uid)";
			 
			$res = $this->registry['db']->prepare($sql);
			$param = array(":fid" => $fid, ":uid" => $this->registry["ui"]["id"]);
			$res->execute($param);

			$target = fopen($this->registry['path']['root'] . "/" . $this->registry['path']['upload'] . $this->md5, "w");
			fseek($temp, 0, SEEK_SET);
			stream_copy_to_stream($temp, $target);
			fclose($target);

			return true;
		} else {
			return false;
		}
	}

	function getExt() {
		$this->filename = $_GET['qqfile'];
		$this->md5 = md5($this->filename . date("YmdHis"));
		
		$pathinfo = pathinfo($_GET['qqfile']);
		return $pathinfo['extension'];
	}

	function getSize() {
		if (isset($_SERVER["CONTENT_LENGTH"])){
			return (int)$_SERVER["CONTENT_LENGTH"];
		} else {
			throw new Exception('Getting content length is not supported.');
		}
	}
}
?>