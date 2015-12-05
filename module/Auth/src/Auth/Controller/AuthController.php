<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ilias
 * Date: 05/12/2015
 * Time: 13:41
 */

namespace Auth\Controller;

use Zend\Session\Container;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;
use Auth\Form\Filter\LoginFilter;
use Auth\Utility\UserPassword;

class AuthController extends AbstractActionController
{
    protected $storage;
    protected $authservice;

    public function loginAction(){
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
                return $this->redirect()->tourl('/auth/login');
                // Logic for login authentication
            }else{
                $errors = $loginForm->getMessages();
                //prx($errors);
            }
        }

        $view->setVariable('loginForm', $loginForm);
        return $view;
    }

    public function logoutAction(){
        $session = new Container('User');
        $session->getManager()->destroy();
        $this->getAuthService()->clearIdentity();
        return $this->redirect()->toUrl('/auth');
    }

    private function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }
        return $this->authservice;
    }
}