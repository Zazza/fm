<?php
class Controller_Users extends Engine_Controller {
	protected $tree;
	protected $muser;

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
		$this->muser = new Model_User();
		
        if (isset($this->args[0])) {
            if ($this->args[0] == "addgroup") {
                
                Controller_Users_Addgroup::index();
                
			} elseif ($this->args[0] == "admin") {
               
               	Controller_Users_Admin::index();
                
            } elseif ($this->args[0] == "structure") {
                
                Controller_Users_Structure::index();

            } elseif ($this->args[0] == "editgroup") {
                
                Controller_Users_Editgroup::index();

            } elseif ($this->args[0] == "adduser") {
                
                Controller_Users_Adduser::index();
                
           } elseif ($this->args[0] == "edituser") {
                
                Controller_Users_Edituser::index();
                
            } elseif ($this->args[0] == "tasks") {
                
                Controller_Users_Tasks::index();
                
            }
        } else {
            Controller_Users_List::index();
        }
    }
}
?>