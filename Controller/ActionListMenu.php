<?php


class AF_Controller_ActionListMenu extends Zend_Controller_Action
{
	protected $_title;
	protected $_listSql;
	protected $_form;
	protected $_dao;
	protected $_fields;
	protected $_redirectUrl = 'index';
	protected $_menu;

	protected $_buffer;


	public function setUserActions($role='user', $actions=Null)
	{
		if(is_null($actions))
		{
			$actions = array(
					'index',
					'insert',
					'update',
					'delete'
					);

		}

		$this->_helper->_aclAF->allow($role, $actions);
	}


	public function setTitle($title)
	{
		$this->_title = $title;
	}

	public function setListQuery($sql)
	{
		$this->_listSql = $sql;
	}

	public function setForm($form)
	{
		$this->_form = $form;
	}

	public function setFields($fields)
	{
		$this->_fields = $fields;
	}

	public function setDAO($dao)
	{
		$this->_dao = $dao;
	}

	public function setRedirectUrl($url)
	{
		$this->_redirectUrl = $ur;
	}

	public function setListMenu($menu, $span = 2)
	{
		$this->_menu = $menu;
		$this->_menuSpan = $span;
	}

	public function init()
	{
		$header = new Twitterbootstrap_PageHeader($this->_title);
		$this->view->buffer = $header->build();
		$this->_helper->viewRenderer->setNoRender(true);
	}

	protected function _build($buffer)
	{
#$header = new Twitterbootstrap_PageHeader($this->_title);
#$buff = $header->build();

		$grid = new Twitterbootstrap_Grid();
#$grid->setFluid();

		$grid->addCell($this->_menu->build()->build(), $this->_menuSpan);
		$grid->addCell($buffer, (12 - $this->_menuSpan));
		$buff = $grid->build();
		echo $buff;
	}


	public function indexAction()
	{
		$controller 	= $this->_request->getControllerName();
		$module 	= $this->_request->getModuleName();


		$title = strtolower($this->_title);
		$this->view->buffer .= "<a href=\"/{$module}/{$controller}/insert\" class=\"btn\"><i class=\"icon-plus-sign\"></i> adicionar {$title}</a>";
		$this->view->buffer .= '<br/><br/>';


		$drop = new Twitterbootstrap_Button_Dropdown('ações');
		$drop->addAction('Alterar','update_{[KEY]}',"/{$module}/{$controller}/update/id/{[KEY]}");
		$drop->addAction('Arquivar','archive_{[KEY]}',"/{$module}/{$controller}/archive/id/{[KEY]}");
		$drop->addDivider();
		$drop->addAction('Apagar','del_{[KEY]}',"/{$module}/{$controller}/delete/id/{[KEY]}");

		$grid = new AF_GridAction();
		$grid->addAction('id', $drop);
		$this->view->buffer .= $grid->build($this->_listSql);

		$this->_build($this->view->buffer);
#echo $this->view->buffer;
	}

	public function insertAction()
	{
		$this->view->buffer .= $this->_form;

		if ($this->_request->isPost()) {
			if ($this->_form->isValid($this->_request->getPost())) {
				$dados = array();
				$formData = $this->_request->getPost();
				foreach($this->_fields as $col)
				{
					$dados[$col] = $formData[$col];
				}

				$this->_dao->insert($dados);

				return $this->_helper->redirector($this->_redirectUrl);
			}
			else
			{
				$this->_form->populate($this->_request->getPost());
			}
		}

#echo $this->view->buffer;
		$this->_build($this->view->buffer);

	} 

	public function updateAction()
	{
		if ($this->_request->isPost()) {
			if ($this->_form->isValid($this->_request->getPost())) {
				$formData = $this->_request->getPost();
				$dados = array();
				foreach($this->_fields as $col)
				{
					$dados[$col] = $formData[$col];
				}

				$where = "id = " . $this->_request->getParam('id');
				$this->_dao->update($dados, $where);

				return $this->_helper->redirector($this->_redirectUrl);
			}
		}

		$data = $this->_dao->find($this->_request->getParam('id'));
		$data = $data->toArray();
		$data = $data[0];

		$this->_form->populate($data);

		$this->view->buffer .= $this->_form;
#echo $this->view->buffer;
		$this->_build($this->view->buffer);
	}

	public function deleteAction()
	{
		$data = array('active' => 1);
		$where = "id = " . $this->_request->getParam('id');
#$data_object->Update($data, $where); 
		$this->_dao->Delete($where);
#delete($where);        
#return $controller->goToUrl($this->_redirectUrl);
		return $this->_helper->redirector($this->_redirectUrl);

	}

}
