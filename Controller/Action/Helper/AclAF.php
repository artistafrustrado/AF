<?php
class AF_Controller_Action_Helper_AclAF extends Zend_Controller_Action_Helper_Abstract
{
    protected $_action;
    protected $_auth;
    protected $_acl;
    protected $_controllerName;

    public function __construct(Zend_View_Interface $view = null, array $options = array())
    {
        $this->_auth = Zend_Auth::getInstance();
        $this->_acl = new Application_Model_ACL();
    }

    public function init()
    {
        $this->_action = $this->getActionController();
        $controller = $this->_action->getRequest()->getControllerName();
        if(!$this->_acl->has($controller)) {
            $this->_acl->add( new Zend_Acl_Resource($controller));
        }
    }

    public function allow($roles = null, $actions = null)
    {
        $resource = $this->_controllerName;
        $this->_acl->allow($roles, $resource, $actions);
        return $this;
    }
    public function deny($roles = null, $actions = null)
    {
        $resource = $this->_controllerName;
        $this->_acl->deny($roles, $resource, $actions);
        Zend_Debug::Dump($this->_acl);
        return $this;
    }

    public function preDispatch()
    {
        $role = 'guest';
        if ($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            if(is_object($user)) {
                $role = $this->_auth->getIdentity()->role;
            }
        }

        $request = $this->_action->getRequest();
        $controller = $request->getControllerName();

        $action = $request->getActionName();
        $module = $request->getModuleName();
        $this->_controllerName = $controller;
        $resource = $controller;
        $privilege = $action;

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        if (!$this->_acl->has($resource)) {
            $resource = null;
        }

        if (!$this->_acl->isAllowed($role, $resource, $privilege)) {

                $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
#$redirector->gotoUrl('/login/');

                if(!$this->_auth->hasIdentity())
                {
                        $request->setModuleName('default');
                        $request->setControllerName('Auth');
                        $request->setActionName('login');
                        $redirector->gotoSimple('login', 'Auth', 'default');
                }
                else
                {
                        $request->setModuleName('default');
                        $request->setControllerName('Auth');
                        $request->setActionName('noperms');
                        $redirector->gotoSimple('noperms', 'Auth', 'default');
                }
        }
    }
}

