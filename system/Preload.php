<?php
class Preload extends Engine_Bootstrap {
    function start() {
        $view = new View_Index();
        $this->registry->set('view', $view);
		
		$ui = new Model_Ui();
		
		if (isset($_POST[session_name()])) {
			session_id($_POST[session_name()]);
		}
		
		session_start();

		$loginSession = & $_SESSION["login"];
		if (isset($loginSession["id"])) {
			$ui->getInfo($loginSession);
		} else if (mb_substr($this->registry["url"], 1, 8) == "download") {
			$download = new Controller_Download();
			$download->index();
			
			exit();
		} else {
			$login = new Controller_Login();
			$login->index();
			 
			exit();
		}
    }
}
?>