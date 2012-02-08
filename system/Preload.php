<?php
class Preload extends Engine_Bootstrap {
    function run() {
        $view = new View_Index();
        $this->registry->set('view', $view);
        
		$view->setDescription($this->registry["keywords"]);
		$view->setKeywords($this->registry["description"]);
		
		$ui = new Model_Ui();

		$loginSession = & $_SESSION["login"];
		if (isset($loginSession["id"])) {
			$ui->getInfo($loginSession);
		} else {
			$login = new Controller_Login();
			$login->index();
			 
			exit();
		}
    }
}
?>