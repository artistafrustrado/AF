<?php


class AF_Controller_ActionCrud extends Zend_Controller_Action
{

	protected $_listSql = "";
	protected $_columns;
	protected $_do;
	protected $_form;
	protected $_redirectTo;
	protected $_title;
	protected $_translate;

	private $__module;
	private $__controller;
	private $__action;

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


	protected function _($string)
	{
#		$this->_translate = Zend_Registry::get('Zend_Translate');
#		return $this->_translate->translate($string);
		return $string;
	}

	protected function _setRedirectUrl($url)
	{
		$this->_redirectTo = $url; 
	}


	public function init()
	{

#$this->_helper->_aclAF->allow('user', $userActions);

#		$this->_translate = Zend_Registry::get('Zend_Translate');
		$this->__module = $this->_request->getModuleName();
		$this->__controller = $this->_request->getControllerName();
		$this->__action = $this->_request->getActionName();
		$this->_setRedirectUrl("/{$this->__module}/{$this->__controller}/index");
		$this->_helper->viewRenderer->setNoRender(true);

	}

	public function indexAction()
	{
		$buffer = "<h1>". $this->_($this->_title)."</h1>\n<br/>";
		$buffer .= "<a href=\"/{$this->__module}/{$this->__controller}/insert\" class=\"cadastrar\">cadastrar</a>\n";
		$buffer .= $this->_helper->crud->IndexList($this->_listSql, $this->_title);
		echo $buffer;
	}


	public function insertAction()
	{
		$buffer = $this->_helper->crud->Insert($this->_form, $this->_do, $this->_columns, $this->_redirectTo, $this->_title);
		echo $buffer;
	}

	public function updateAction()
	{
		$buffer = $this->_helper->crud->Update($this->_form, $this->_do, $this->_columns, $this->_redirectTo, $this->_title);
		echo $buffer;
	}

	public function deleteAction()
	{
		$msg = 'Tem certeza que deseja apagar?'; 
		$buffer = $this->_helper->crud->Delete($this->_do, $msg, $this->_redirectTo, $this->_title);
		echo $buffer;
	}

	public function goToUrl($place)
	{
		return $this->_helper->redirector->gotourl($place);
	}
}
