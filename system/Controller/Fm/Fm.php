<?php
class Controller_Fm_Fm extends Engine_Controller {

	public function index() {
		$this->view->setTitle("Ostora Filemanager");
		
		$file = new Model_File();
		$file->showTree(0);
		
		if (isset($_GET["id"])) {
			$fm = & $_SESSION["fm"];

			if ($_GET["id"] == "0") {
				$fm["dir"] = 0;
				$fm["dirname"] = "/";
			} else if (is_numeric($_GET["id"])) {
				$fm["dir"] = $_GET["id"];
				$dirname = $file->getNameFromDir($_GET["id"]);
				$fm["dirname"] = $dirname[0]["name"];
			} else {
				$fm["dir"] = 0;
				$fm["dirname"] = "/";
			}
		}
		
		$this->view->setLeftContent($this->view->render("block_left", array("tree" => $file->getTree())));
		$this->view->setBottom($this->view->render("block_bottom", array()));
		
		$this->view->index(array("session_name" => session_name(), "session_id" => session_id(), "maxUploadSize" => $this->registry["fm"]["maxUploadSize"]));
	}
}
?>