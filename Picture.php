<?php

class AF_Picture
{

	protected $_destination;
	protected $_modelId;

	public function __construct($destination, $modelId)
	{
		$this->_destination = $destination;
		$this->_modelId = $modelId;
		$this->_watermark = APPLICATION_PATH.'/modules/Model/data/img/watermark.png';
		$this->_picSize = array('width' => 640, 'height' => 480);
		$this->_thumbSize = array('width' => 150, 'height' => 150);
	}

	protected function _checkAndSetUpDir()
	{
		if(!is_dir($this->_destination))
		{
			mkdir($this->_destination, 0777, TRUE);
			mkdir($this->_destination.'/ori', 0777, TRUE);
			mkdir($this->_destination.'/pic', 0777, TRUE);
			mkdir($this->_destination.'/thumb', 0777, TRUE);
		}
	}

	public function processImage($file, $filePath)
	{
		$this->_checkAndSetUpDir();
		$this->_processImage($file, $filePath);
	}

	protected function _processImage($file, $filePath)
	{


	} 

}

