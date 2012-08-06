<?php

class AF_Upload
{

	protected $_form;
	protected $_dao;
	protected $_request;

	public function setForm($form)
	{
		$this->_form = $form;
	}

	public function setDAO($dao)
	{
		$this->_dao = $dao;
	}

	public function setRequest($request)
	{
		$this->_request = $request;
	}

	public function setDestination($path)
	{
		$this->_destination = $path;
		
		#echo $path;
#		exit;
		if(!is_dir($path))
		{
			mkdir($path, 0777, true);
			mkdir($path.'/original', 0777, true);
			mkdir($path.'/thumb', 0777, true);
			mkdir($path.'/site', 0777, true);
		}
	}

	public function setListUrl($url)
	{
		$this->_listUrl = $url;
	}

	public function build()
	{
		$buffer = '';

		$data = $this->_request->getPost();
		if ($this->_form->isValid($data))
		{

			Zend_Debug::Dump($data);
			$adapter = new Zend_File_Transfer_Adapter_Http();

			$adapter->setDestination($this->_destination);

			if (!$adapter->receive()) 
			{
				$messages = $adapter->getMessages();
				echo implode("\n", $messages);
			}

			$this->upload();
		}

		$images = $this->listImages($this->_destination, $this->_listUrl);

		$grid = new Twitterbootstrap_Grid();
		foreach($images as $image)
		{
			$button = new Twitterbootstrap_Button('<i class="icon-trash"></i> excluir', '/Admin/Program-picture/remove');
			$content = $image . "<br/>\n". $button->build();
			$grid->addCell($content, 2);
		}
		$buffer .= $grid->build();

/*
		$thumb = new Twitterbootstrap_Thumbnails(2);
		foreach($images as $image)
		{
			$button = new Twitterbootstrap_Button('<i span="icon-trash"></i> excluir', '/Admin/Program-picture/remove');
			$thumb->addImage($image, $image, $image, '', $button->build());
		}

		$buffer .= $thumb->build();
*/
		$buffer .= $this->_form;

		return $buffer;
	}

	public function upload()
	{
		$upload = new Zend_File_Transfer();

		$files = $upload->getFileInfo();

		foreach ($files as $file => $info) {
			Zend_Debug::Dump($file);
			Zend_Debug::Dump($info);
			// file uploaded ?
			if (!$upload->isUploaded($file)) {
				print "Why havn't you uploaded the file ?";
				continue;
			}

/*
			// validators are ok ?
			if (!$upload->isValid($file)) 
			{
				print "File: {$file}<br/>\n";
				print "Sorry but $file is not what we wanted";
				continue;
			}
*/
		}

		$upload->receive();
	}

	public function convertFile($file)
	{


	}


	public function listImages($path, $url)
	{
		#$buffer = "";
		$images = array();
		$iterator = new DirectoryIterator($path);
		foreach ($iterator as $fileinfo) {
			if ($fileinfo->isFile()) {
				#$filenames[$fileinfo->getMTime()] = $fileinfo->getFilename();
				$filename = $fileinfo->getFilename();
				#$buffer .= "<img src=\"{$url}/{$filename}\"/>\n";
				$images[] = $this->makeCell("{$url}/{$filename}");;
				#$images[] = "{$url}/{$filename}";;
			}
		}

		return $images;
		#return $buffer;
	}

	public function makeCell($image)
	{
		$image = "<img src=\"{$image}\"/>\n";
		return $image;

	}

}
