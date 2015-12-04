<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\LoginForm;
use Application\Form\Filter\LoginFilter;
use Application\Utility\UserPassword;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $request = $this->getRequest();

        $view = new ViewModel();
        $loginForm = new LoginForm('loginForm');
        $loginForm->setInputFilter(new LoginFilter());

        if ($request->isPost()) {
            $data = $request->getPost();
            $loginForm->setData($data);

            if ($loginForm->isValid()) {
                $data = $loginForm->getData();

                $userPassword = new UserPassword();
                $encyptPass = $userPassword->create($data['password']);

                $authService = $this->getServiceLocator()->get('AuthService');

                $authService->getAdapter()
                    ->setIdentity($data['email'])
                    ->setCredential($encyptPass);

                $result = $authService->authenticate();

                if ($result->isValid()) {

                    $userDetails = $this->_getUserDetails(array(
                        'email' => $data['email']
                    ), array(
                        'user_id'
                    ));

                    $session = new Container('User');
                    $session->offsetSet('email', $data['email']);
                    $session->offsetSet('userId', $userDetails[0]['user_id']);
                    $session->offsetSet('roleId', $userDetails[0]['role_id']);
                    $session->offsetSet('roleName', $userDetails[0]['role_name']);

                    $this->flashMessenger()->addMessage(array(
                        'success' => 'Login Success.'
                    ));
                    // Redirect to page after successful login
                } else {
                    $this->flashMessenger()->addMessage(array(
                        'error' => 'invalid credentials.'
                    ));
                    // Redirect to page after login failure
                }
                return $this->redirect()->tourl('/login');
                // Logic for login authentication
            } else {
                $errors = $loginForm->getMessages();
                // prx($errors);
            }
        }

        $view->setVariable('loginForm', $loginForm);
        return $view;
    }

    private function _getUserDetails($where, $columns)
    {
        $userTable = $this->getServiceLocator()->get("UserTable");
        $users = $userTable->getUsers($where, $columns);
        return $users;
    }
}
