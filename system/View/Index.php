<?php
class View_Index extends Engine_View {

    public $leftBlock = null;
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

	public function showPage() {
		$template = $this->main->loadTemplate("page.html");
		$template->display(array("registry" => $this->registry,
										"title" => $this->title,
										"leftBlock" => $this->leftBlock,
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
