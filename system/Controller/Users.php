<?php
class Controller_Users extends Engine_Controller {
	protected $tree;
	protected $muser;
	
	function __construct() {
		parent::__construct();
		$this->muser = new Model_User();
	}

	protected function print_array($arr) {
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
						$gid = $this->muser->getSubgroupName($key);
						$this->tree .= "<ul><li><span class='folder'><label>" . $gid . "</label></span>";
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
	
	public function index() {
        if (isset($this->args[0])) {
            if ($this->args[0] == "addgroup") {
                
                $controller = new Controller_Users_Addgroup;
                $controller->index();
                
			} elseif ($this->args[0] == "structure") {
                
                $controller = new Controller_Users_Structure;
                $controller->index();

            } elseif ($this->args[0] == "editgroup") {
                
                $controller = new Controller_Users_Editgroup;
                $controller->index();

            } elseif ($this->args[0] == "adduser") {
                
                $controller = new Controller_Users_Adduser;
                $controller->index();
                
           } elseif ($this->args[0] == "edituser") {
                
                $controller = new Controller_Users_Edituser;
                $controller->index();
                
            }
        } else {
            $controller = new Controller_Users_List;
            $controller->index();
        }
    }
}
?>