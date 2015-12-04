<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ilias
 * Date: 04/12/2015
 * Time: 11:26
 */

namespace Application\Controller;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Application\Controller\IndexController;
use Application\Form\LoginForm;
use Application\Form\Filter\LoginFilter;
use Application\Utility\UserPassword;

class LogoutController extends IndexController {
    protected $storage;
    protected $authservice;

    public function indexAction(){
        $session = new Container('User');
        $session->getManager()->destroy();
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toUrl('/application/login');
    }

    private function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }

}