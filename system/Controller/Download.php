<?php
class Controller_Download extends Engine_Controller {

	public function index() {
		$mfile = new Model_File();
		
		if (isset($_GET["filename"])) {
			$filename = $_GET["filename"];
			
			$data = $mfile->getMD5FromName($filename);

			if (count($data) > 0) {
				$fn = $data[0]["md5"];

				$file = $this->registry["rootPublic"] . $this->registry["path"]["upload"] . $fn;

				if (file_exists($file)) {
					$filename = str_replace(" ", "_", $filename);

					header ("Content-Type: application/octet-stream");
					header ("Accept-Ranges: bytes");
					header ("Content-Length: " . filesize($file));
					header ("Content-Disposition: attachment; filename=" . $filename);

					if ($this->registry["server"]["nginx_accel_redirect"] == true) {
						header("X-Accel-Redirect: /upload/" . $fn);
					}
					else {
						readfile($file);
					}
				} else {
					echo $this->view->render("fileNotExist", array());
				}
			} else {
				echo $this->view->render("fileNotExist", array());
			}
		} else {
			echo $this->view->render("fileNotExist", array());
		}
		
		exit();
	}
}
?>
