<?php
class Engine_Router extends Engine_Interface {
	private $args;

	function __construct() {
		parent::__construct();
	}

	private function getArgs($arguments) {
		foreach($arguments as $part) {
			$this->args[] = quotemeta($part);
		}
	}
	
	function showContent() {
        
		$action = (empty($_GET['main'])) ? '' : $_GET['main'];
        if (empty($action)) { $action = 'index'; };
		
		$action = trim($action, '/\\');
		$parts = explode('/', $action);

		$action = array_shift($parts);
		$action = mb_strtolower(quotemeta($action));
		
		$arguments = $parts;
		
		$this->getArgs($arguments);

		if (isset($_POST["action"])) {
			$this->registry->set("action", $_POST["action"]);
			unset($_POST["action"]);
		} else if (isset($_GET["action"])) {
			$this->registry->set("action", $_GET["action"]);
		}
		$this->registry->set("args", $this->args);
		$this->registry->set("get", $_GET);
		$this->registry->set("post", $_POST);

		if ( ($action == "ajax") and (isset($parts[0])) ) {

			if ( (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) and ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') ) {
	    		$class = "Controller_Ajax_" . ucfirst($this->args[0]);
	    		$controller = new $class();
	    		
	    		$method = $this->registry["action"];
	    		$controller->$method($this->registry["post"]);
	    		
	    		exit();
			}
		} else {
			if (!is_file($this->registry["controller"] . ucfirst($action) . '.php')) {
			
				$class = 'Engine_Controller';
				$controller = new $class();
				
				$controller->__call();
			} else {
				$class = 'Controller_' . ucfirst($action);
				$controller = new $class();
				
				$controller->index();
			}
		}
	}
}

?>