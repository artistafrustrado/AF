<?php

// http://www.google.com/webfonts/

class AF_GoogleFonts
{

	protected $_fonts;

	public function __construct()
	{
		$this->_fonts = array();
	}

	public function addFont($font)
	{
		$this->_fonts[] = $font;
	}
	
	public function build($view)
	{
		$fonts = join ('|',$this->_fonts);
		$css = "http://fonts.googleapis.com/css?family={$fonts}&subset=latin,latin-ext"; 	
	
		$view->headLink()->appendStylesheet($css);

	}


}
