<?php
class Model_File extends Engine_Model {
	private $_MIME = array(
		array("name" => "img", "ico" => "image.png", "ext" => array("jpg", "jpeg", "gif", "png", "bmp")),
		array("name" => "doc", "ico" => "msword.png", "ext" => array("doc", "docx", "rtf", "oft")),
		array("name" => "pdf", "ico" => "pdf.png", "ext" =>  array("pdf", "djvu")),
		array("name" => "txt", "ico" => "text.png", "ext" =>  array("txt")),
		array("name" => "flv", "ico" => "flash.png", "ext" =>  array("flv")),
		array("name" => "exe", "ico" => "executable.png", "ext" =>  array("exe", "com", "bat")),
		array("name" => "xls", "ico" => "excel.png", "ext" =>  array("xls", "xlsx")),
		array("name" => "mp3", "ico" => "audio.png", "ext" =>  array("mp3", "wav", "flac")),
		array("name" => "html", "ico" => "html.png", "ext" =>  array("html", "htm", "php", "js")),
		array("name" => "zip", "ico" => "compress.png", "ext" =>  array("zip", "rar", "7z", "tar", "bz2", "gz"))
	);

	public function setIcon($ext) {
		$ico = "unknown.png";
		
		for($i=0; $i<count($this->_MIME); $i++) {
			if (in_array($ext, $this->_MIME[$i]["ext"])) $ico = $this->_MIME[$i]["ico"];
		}
		
		return $ico;
	}
}
?>