<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ilias
 * Date: 26/11/2015
 * Time: 19:34
 */

namespace Application\Controller;

use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Application\Controller\IndexController;
use Application\Form\LoginForm;
use Application\Form\Filter\LoginFilter;
use Application\Utility\UserPassword;

class LoginController extends IndexController
{
    protected $storage;
    protected $authservice;

    public function indexAction(){

        $request = $this->getRequest();

        $view = new ViewModel();
        $loginForm = new LoginForm('loginForm');
        $loginForm->setInputFilter(new LoginFilter() );

        if($request->isPost()){
            $data = $request->getPost();
            $loginForm->setData($data);

            if($loginForm->isValid()){
                $data = $loginForm->getData();

                $userPassword = new UserPassword();
                $encyptPass = $userPassword->create($data['password']);

                $this->flashMessenger()->addMessage(array('password' => $encyptPass));
                $this->getAuthService()
                    ->getAdapter()
                    ->setIdentity($data['email'])
                    ->setCredential($encyptPass);
                $result = $this->getAuthService()->authenticate();

                if ($result->isValid()) {

                    $session = new Container('User');
                    $session->offsetSet('email', $data['email']);

                    $this->flashMessenger()->addMessage(array('success' => 'Login Success.'));
                    // Redirect to page after successful login
                }else{
                    $this->flashMessenger()->addMessage(array('error' => 'invalid credentials.'));
                    // Redirect to page after login failure
                }
                return $this->redirect()->tourl('/application/login');
                // Logic for login authentication
            }else{
                $errors = $loginForm->getMessages();
                //prx($errors);
            }
        }

        $view->setVariable('loginForm', $loginForm);
        return $view;
    }

    private function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }
}