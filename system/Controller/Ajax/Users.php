<?php
class Controller_Ajax_Users extends Engine_Ajax {
	private $muser;
	
	public function __construct() {
		parent::__construct();
		$this->muser = new Model_User();
	}
	
    public function delGroup($params) {
        $gid = $params["gid"];
        
        $this->muser->delGroup($gid);
    }
    
    public function delUser($params) {
        $uid = $params["uid"];
        
        $this->muser->delUser($uid);
    }
    
    public function getUser($params) {
        $id = $params["id"];
        $type = $params["type"];
        
        if ($type == "u") {
            
            $data = $this->muser->getUserInfo($id);
            echo "<p><span id='udesc[" . $data["uid"] . "]' style='font-size: 11px; margin-right: 10px'>" . $data["name"] . " " . $data["soname"] . "</span>";
            echo '<input id="uhid[' . $data["uid"] . ']" type="hidden" name="ruser[]" value="' . $data["uid"] . '" /></p>';
            
        } elseif ($type == "g") {

            $gname = $this->muser->getGroupName($id);
            echo '<p style="font-size: 11px; margin-right: 10px">' . $gname . '<input type="hidden" name="gruser[]" value="' . $id . '" /></p>';
        } elseif ($type == "all") {

            echo '<p style="font-size: 11px; margin-right: 10px">Все<input type="hidden" name="rall" value="1" /></p>';
        }
    }
    
    public function spam($params) {
        $tid = $params["tid"];
        
        $this->muser->spam($tid);
    }

    public function getUI($params) {
    	$uid = $params["uid"];
    	
    	$data = $this->muser->getUserInfo($uid);
    	
    	echo $this->view->render("userInfo", array("post" => $data));
    }

    public function getTree($params) {
        $pid = $params["pid"];

        $tree = $this->muser->getSubgroups($pid);
        
        echo $this->view->render("users_structure", array("tree" => $tree));
    }
    
	public function addTree($params) {
        $pid = $params["pid"];
        $name = htmlspecialchars($params["name"]);

        $this->muser->addSubgroup($pid, $name);
    }
    
	public function delCat($params) {
        $id = $params["id"];

        $this->muser->delSubgroup($id);
    }
    
	public function editCat($params) {
        $id = $params["id"];
        $name = htmlspecialchars($params["name"]);
        
        $this->muser->editCat($id, $name);
    }
    
    public function getCatName($params) {
        $id = $params["id"];
        
        $cat = $this->muser->getCatName($id);
         
        echo $cat["name"];
    }
}
?>