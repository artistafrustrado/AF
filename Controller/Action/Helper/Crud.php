<?php
class AF_Controller_Action_Helper_Crud extends Zend_Controller_Action_Helper_Abstract
{
	protected $_action;
	protected $_auth;
	protected $_acl;
	protected $_controllerName;

	public function __construct(Zend_View_Interface $view = null, array $options = array())
	{
#        $this->_auth = Zend_Auth::getInstance();
#        $this->_acl = new AF_Acl_AFAcl();
	}

	public function init()
	{
		$this->_action = $this->getActionController();
		$controller = $this->_action->getRequest()->getControllerName();
	}


	public function preDispatch()
	{

	}

	public function Update($form, $data_table, $dados_cols, $redirect_to, $title=Null)
	{
		$controller = $this->getActionController();
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
			$formData = $request->getPost();
			if ($form->isValid($request->getPost()))
			{
				$dados = array();
				foreach($dados_cols as $col)
				{
					$dados[$col] = $formData[$col];
				}
				$where = "id = " . $request->getParam('id');
				$data_table->update($dados, $where);
				return $controller->goToUrl($redirect_to);
			}
		}
		$dados = $data_table->find($this->getRequest()->getParam('id'));
		$dados = $dados->toArray();
		$dados = $dados[0];

		$form->populate($dados);

		$buffer = "<h1>Editar ". $title."</h1>\n\n<br/>\n";
		$buffer .= $form;

		return $buffer;

	}

	public function Insert($form, $data_table, $dados_cols, $redirect_to, $title=null, $dados_adicionais=null)
	{
		$controller = $this->getActionController();
		$request = $this->getRequest();
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($request->getPost())) {
				$formData = $request->getPost();
				$dados = array();
				foreach($dados_cols as $col)
				{
					$dados[$col] = $formData[$col];
				}
				if($dados_adicionais != null)
				{
					foreach($dados_adicionais as $key => $val)
					{
						$dados[$key] = $val;
					}
				}

				$data_table->insert($dados);
				return $controller->goToUrl($redirect_to);
			}
		}
#		$translate = Zend_Registry::get('Zend_Translate');
		$buffer = "<h1>Inserir {$title}</h1>\n\n<br/>\n";
		$buffer .= $form;

		return $buffer;

	}

	public function IndexList($sql, $title=Null)
	{
		$report = new AF_Relatorio();
		$report->MontarGrade($sql);
#		$buffer  = "<h1>{$title}</h1>\n";
		$buffer  = "<h1></h1>\n";
		$buffer .= $report->Montar();;

		return $buffer;
	}

	public function Delete($data_object, $msg, $redirect_to, $title=Null)
	{
		$controller = $this->getActionController();
		$request = $this->getRequest();
		$id = $this->getRequest()->getParam('id');
		$buffer  = "<h1>Apagar {$title}</h1>\n";

		if($this->getRequest()->getParam('confirm') == 'yes')
		{
			$data = array('active' => 1);
			$where = "id = " . $request->getParam('id');
			#$data_object->Update($data, $where); 
			$data_object->Delete($where); 
			#delete($where);	
			return $controller->goToUrl($redirect_to);
		} 
		elseif($this->getRequest()->getParam('confirm') == 'no')
		{
			return $controller->goToUrl($redirect_to);
		} 
		else
		{
			$buffer .= "<div id='confirm_delete'>\n";
			$buffer .= "<p>{$msg}</p>";
			$buffer .= "<div id=\"confirm\"><a href=\"{$_SERVER['REQUEST_URI']}/confirm/yes\" class='confirm_delete_option'>sim</a> ";
			$buffer .= " <a href=\"{$_SERVER['REQUEST_URI']}/confirm/no\" class='confirm_delete_option'>não</a></div>\n";
			$buffer .= "</div>\n";
		} 
		if(isset($form))
		{
			$buffer .= $form;
		}
		return $buffer;
	}
}


