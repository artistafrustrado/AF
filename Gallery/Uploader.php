<?php

class AF_Gallery_Uploader
{

	protected $_destination;
	protected $_processor;


	public function __construct()
	{
		$fc = Zend_Controller_Front::getInstance();
		$this->_request = $fc->getRequest();
		$this->_processor = new AF_Gallery_UploadProcess();
	}


        public function setDestination($path)
        {
                $this->_destination = $path;

                if(!is_dir($path))
                {
                        mkdir($path, 0777, true);
                        mkdir($path.'/original', 0777, true);
                        mkdir($path.'/thumb', 0777, true);
                        mkdir($path.'/site', 0777, true);
                }
        }

	public function setDAO($dao)
        {
                $this->_dao = $dao;
        }

	public function buildForm()
	{
		$form = new Twitterbootstrap_Form();
		$form->setMethod('post');


		$dao = new Admin_Model_DbTable_Host();
		$dos = $dao->listIdName();

		$form->addElement('file', 'uploadfile', array(
					'label'      => 'Arquivos',
					'required'   => true,
					'multiple'   => 'multiple',
					'filters'    => array('StringTrim'),
					));
		$file = $form->getElement('uploadfile');
		$file->setIsArray(true);


		$form->addElement('hidden', 'id_program', array());

		$form->addElement('submit', 'submit', array(
					'ignore'   => true,
					'label'    => 'enviar',
					'class'    => 'btn btn-primary',
					));

		return $form;
	}


	public function upload($destDir)
	{
		$this->setDestination($destDir);              
		$this->_processor->setDAO($this->_dao);		

		$destDir = $destDir.'/original/';
 
		$this->_form = $this->buildForm();
		$data = $this->_request->getPost();
                if ($this->_form->isValid($data))
                {

                        Zend_Debug::Dump($data);
                        $adapter = new Zend_File_Transfer_Adapter_Http();

                        $adapter->setDestination($destDir);

                        if (!$adapter->receive())
                        {
                                $messages = $adapter->getMessages();
                                echo implode("\n", $messages);
                        }

                }


		$upload = new Zend_File_Transfer();

		$files = $upload->getFileInfo();

		foreach ($files as $file => $info) {
			Zend_Debug::Dump($info);
			$file = "{$destDir}{$info['name']}";
			$this->_processor->process($file);

			if (!$upload->isUploaded($file)) {
				print "Why havn't you uploaded the file ?";
				continue;
			}

		}

	}

}
