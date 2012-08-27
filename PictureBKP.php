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

		// IMAGES

		$watermark = new Imagick($this->_watermark);
		$watermarkWidth  = $watermark->getImageWidth();
		$watermarkHeight = $watermark->getImageHeight(); 
                #$watermark->thumbnailImage(190, 0);
                $image = new Imagick($filePath);
                $image->thumbnailImage($this->_picSize['width'], $this->_picSize['height'], true);

                $canvas = new Imagick();
                $width = $image->getImageWidth();
                $height = $image->getImageHeight();
                $canvas->newImage($width, $height, new ImagickPixel("black"));
                $canvas->setImageFormat("png");
                $canvas->compositeImage($image, imagick::COMPOSITE_OVER, 0, 0);
                $canvas->compositeImage($watermark, imagick::COMPOSITE_OVER, ($width - ($watermarkWidth + 5)), ($height - ($watermarkHeight + 5)));

		$pic = str_replace('/ori/','/pic/', $filePath);
		$pic = str_replace('jpg','png', $pic);
		$pic = str_replace('jpeg','png', $pic);
                $canvas->writeImage($pic);
	
		// THUMBS
                $image = new Imagick($filePath);
		$image->cropThumbnailImage($this->_thumbSize['width'], $this->_thumbSize['height']);
                $image->setImageFormat("png");
		
		$thumb = str_replace('/ori/','/thumb/', $filePath);
		$thumb = str_replace('jpg','png', $thumb);
		$thumb = str_replace('jpeg','png', $thumb);
                $image->writeImage($thumb);

	}


	public function upload($form)
	{

		$this->_checkAndSetUpDir();

		foreach($_FILES as $key => $file)
		{
			if(!is_null($file['type']))
			{
				#echo "<li>key: {$key}</li>";
				#Zend_Debug::Dump($file);
				
				$fullFilePath = $this->_destination.'/ori/'.$file['name'];
				move_uploaded_file($file['tmp_name'], $fullFilePath);
				$pic = new Model_Model_DbTable_ModelPicture();
				$data = array(
					'id_model' 	=> $this->_modelId,
					'name'		=> $file['name'],
					'mime_type'	=> $file['type'],
					'type'		=> 'P',
				);
				$pic->insert($data);
				$this->_processImage($file, $fullFilePath); 
			}
		}

/*
		$upload = new Zend_File_Transfer_Adapter_Http();
		$upload->setDestination($this->_destination);
		try 
		{
			// upload received file(s)
			$upload->receive();
		} 
		catch (Zend_File_Transfer_Exception $e) 
		{
			$e->getMessage();
		}

		$uploadedData = $form->getValues();
		Zend_Debug::dump($uploadedData, 'Form Data:');

		$files = $upload->getFileInfo();

		#foreach($uploadedData as $file)
		foreach($files as $file)
		{

			if(!is_null($file['type']))
			{
				Zend_Debug::Dump($file);
				#$name = $upload->getFileName($file);
				$name = $file['name'];
				echo "<li>{$name}</li>\n";
				$fileName = $this->_destination.'/ori/'.$name;
				rename($file['tmp_name'], $fileName); 
				#$path_parts = pathinfo($name);
				#$renameFile = $path_parts['basename']; 
				#$renameFile = $upload->getFileName($file, false);
				#$fullFilePath = $this->_destination.'/ori/'.$name;
				#$filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));
				#$filterFileRename->filter($upload);
			}
		}

*/
/*
		$name = $upload->getFileName('doc_path');
		#$upload->setOption(array('useByteString' => false));
		$size = $upload->getFileSize('doc_path');

		$mimeType = $upload->getMimeType('doc_path');
		print "Name of uploaded file: $name";
		print "File Size: $size";
		print "File's Mime Type: $mimeType";

		$renameFile = 'newName.jpg';
		$fullFilePath = '/tmp/model/'.$renameFile;
		$filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));
		$filterFileRename -> filter($name);
*/
	} 

}

