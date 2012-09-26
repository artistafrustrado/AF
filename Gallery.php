<?php

class AF_Gallery
{

	protected $_uploader;
	protected $_viewer;
	protected $_imgProcessor;
	protected $_request;
	protected $_dao;


	public function __construct()
	{
		$this->_uploader = new AF_Gallery_Uploader();
		$fc = Zend_Controller_Front::getInstance();
		# $fc->getRequest()->getParam('id');
		$this->setRequest($fc->getRequest());
		$this->_form = $this->_uploader->buildForm();
	}

	public function setUploader($uploader)
	{
		$this->_uploader = $uploader;
	}

	public function setViewer($viewer)
	{
		$this->_viewer = $viewer;
	}

	public function setImgProcessor($imgProcessor)
	{
		$this->_imgProcessor = $imgProcessor;
	}

	public function setRequest($request)
	{
		$this->_request = $request;
	}

	public function setDAO($dao)
	{
		$this->_dao = $dao;
	}


	public function buildUpload($destDir)
	{
		$this->_uploader->setDAO($this->_dao);
		$buffer = '';

               $data = $this->_request->getPost();
                if ($this->_form->isValid($data))
                {
			$this->_uploader->upload($destDir);
		}

		
		$buffer .= $this->_form;
		return $buffer;
	}


	public function buildGallery($id_program)
	{
		$module     = $this->_request->getModuleName();
		$controller = $this->_request->getControllerName();

		$db = Zend_Db_Table::getDefaultAdapter();
		$grid = new Twitterbootstrap_Thumbnails(2); 

		$active = true;
		$sql = "SELECT * FROM program_picture WHERE id_program = {$id_program} AND active = $active";

		$rows = $db->fetchAll($sql);
		if($rows)
		{
			foreach($rows as $row)
			{
				$text  = '<div style="text-align: center;">';
				$text .= $row['title']."<br/><br/>\n";
				$text .= "<a href=\"/{$module}/{$controller}/delete/id/{$row['id']}/id_program/{$id_program}\" class=\"btn btn-mini btn-danger\" style='float:center;'><i class='icon-trash icon-white'></i> apagar</a>\n";
				$text .= "<a  href=\"#\" url=\"/Admin/Program-Picture/geteditform/id_program/1/id/{$row['id']}\" class=\"btn btn-mini btn-primary program-picture\"><i class='icon-file icon-white'></i> editar</a>\n";
				$text .= '</div>';
				$grid->addImage("{$row['path']}", $row['name'], "{$row['path']}", '', $text);

			}
		}

		$buffer = ''; 
		$buffer .= $this->getModal();
		$buffer .= $grid->build();

		return $buffer;
	}

	public function getModal()
	{
		$buffer = "<div class=\"modal hide fade\" id=\"ProgramPictureModal\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"ProgramPictureModalLabel\" aria-hidden=\"true\"> \n";
		$buffer .= "  <div class=\"modal-header\"> \n";
		$buffer .= "    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-hidden=\"true\">×</button> \n";
		$buffer .= "    <h3 id=\"ProgramPictureModalLabel\">Foto de Programa</h3> \n";
		$buffer .= "  </div> \n";
		$buffer .= "  <div class=\"modal-body\" id=\"modal-body\"> \n";
		$buffer .= "    <p>One fine body…</p> \n";
		$buffer .= "  </div> \n";
		$buffer .= "  <div class=\"modal-footer\"> \n";
		$buffer .= "    <button class=\"btn\" data-dismiss=\"modal\" aria-hidden=\"true\">fechar</button> \n";
		$buffer .= "    <button class=\"btn btn-primary\" id=\"program-picture-modal-save\">salvar</button> \n";
		$buffer .= "  </div> \n";
		$buffer .= "</div> \n";

				$text .= "<a href=\"/{$module}/{$controller}/delete/id/{$row['id']}/id_program/{$id_program}\" class=\"btn btn-mini btn-danger\" style='float:center;'><i class='icon-trash icon-white'></i> apagar</a>\n";
				$text .= "<a href=\"/{$module}/{$controller}/edit/id/{$row['id']}/id_program/{$id_program}\" class=\"btn btn-mini btn-primary\" style='float:center;'><i class='icon-file icon-white'></i> editar</a>\n";
				#$text .= "<a href=\"/{$module}/{$controller}/delete/id/{$row['id']}/id_program/{$id_program}\" class=\"btn\" style='float:center;'><i class='icon-trash'></i> apagar</a>";
				$text .= '</div>';
				$grid->addImage("/media/program/images/{$id_program}/thumb/{$row['name']}", $row['name'], "/media/program/images/{$id_program}/thumb/{$row['name']}", '', $text);

			}

		}

		$buffer = $grid->build();
		return $buffer;
	}

}

