<?php
class Engine_Ajax extends Engine_Interface {
	
	protected $view;
    protected $memcached;

	function __construct() {
		parent::__construct();
		
		$this->view = $this->registry['view'];
	}
    
    public function __call($name, $args) {
        $action = $args[0]["action"];
        $this->errorload($action);
    }
    
    private function errorload($name) {
        echo "<p>Error load Ajax controller: " . $name . "</p>";
    }
}
?>