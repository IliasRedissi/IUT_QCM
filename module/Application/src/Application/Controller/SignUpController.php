<?php
/**
 * Created by PhpStorm.
 * User: axell
 * Date: 04/12/15
 * Time: 10:17
 */

namespace Application\Controller;

use Application\Form\Filter\SignUpFilter;
use Application\Form\SignUpForm;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Application\Controller\IndexController;
use Application\Form\LoginForm;
use Application\Form\Filter\LoginFilter;
use Application\Utility\UserPassword;

class SignUpController extends IndexController
{
    public function indexAction()
    {

        $session = new Container('User');


        if ($session->getManager()->isValid()) {
            return $this->redirect()->toUrl('/application/index');
        }
        //else

        $request = $this->getRequest();

        $view = new ViewModel();
        $signupForm = new SignUpForm('signupForm');
        $signupForm->setInputFilter(new SignUpFilter());


        if ($request->isPost()) {
            $data = $request->getPost();
            $signupForm->setData($data);

            if ($signupForm->isValid()) {
                $data = $signupForm->getData();

                $userPassword = new UserPassword();
                $encyptPass = $userPassword->create($data['password']);

                /*
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
                return $this->redirect()->tourl('/application/signup');
                // Logic for login authentication
                */
            } else {
                $errors = $signupForm->getMessages();
                //prx($errors);
            }
        }

        $view->setVariable('signupForm', $signupForm);
        return $view;
    }


}