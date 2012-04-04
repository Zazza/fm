<?php
class View_Index extends Engine_View {

    private $leftBlock = null;
    private $bottom = null;
    private $content = null;
    private $profile;
    private $topMenu = null;
    private $fastmenu = null;
    private $fastmenu_notdrop = null;
    private $css = null;
    private $js = null;
    
    public function setLeftContent($text) {
        $this->leftBlock .= $text;
    }
    
    public function setBottom($text) {
    	$this->bottom .= $text;
    }

	public function showPage() {
		$template = $this->main->loadTemplate("page.html");
		$template->display(array("registry" => $this->registry,
										"title" => $this->title,
										"leftBlock" => $this->leftBlock,
										"bottom" => $this->bottom,
                                		"main_content" => $this->mainContent,
										"content" => $this->content));
	}
	
	public function setContent($content) {
		$this->content .= $content;
	}
	
	public function setProfile($content) {
		$this->profile = $content;
	}
}
?>
