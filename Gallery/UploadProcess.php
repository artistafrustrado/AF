<?php

class AF_Gallery_UploadProcess
{

	public function __construct()
	{
		$this->_picSize = array('width' => 640, 'height' => 480);
		$this->_thumbSize = array('width' => 150, 'height' => 150);
	}

        public function setDAO($dao)
        {
                $this->_dao = $dao;
        }

	public function process($file)
	{
		$origin = $file;
		$new = str_replace('/original/','/thumb/', $file);
		$this->_resize($origin, $new, $this->_thumbSize['width'], $this->_thumbSize['height']);
		$new = str_replace('/original/','/site/', $file);
		$this->_resize($origin, $new, $this->_picSize['width'], $this->_picSize['height']);
		$this->_insertDB($file);
	}


	protected function _resize($origin, $new, $width, $height)
	{
		$image = new Imagick($origin);
		$image->cropThumbnailImage($width, $height);
		$image->setImageFormat("png");

#		$new = str_replace('/original/','/new/', $origin);
		$new = str_replace('jpg','png', $new);
		$new = str_replace('jpeg','png', $new);
		
		echo "<h1>ORI: {$origin} NEW: {$new}</h1>";
#		return;
		$image->writeImage($new);
	}

	protected function _insertDB($file)
	{
		$info = pathinfo($file);
	
		$data = array();
		$data['id_program'] = '3';
		$data['name'] = $info['filename'].".png";
		$data['original_name'] = $info['filename'];
		$this->_dao->insert($data);

	}

}
