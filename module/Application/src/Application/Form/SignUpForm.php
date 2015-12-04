<?php
/**
 * Created by PhpStorm.
 * User: axell
 * Date: 04/12/15
 * Time: 09:44
 */

namespace Application\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\Form\Element\Csrf;

class SignUpForm extends Form
{
    public function __construct($name)
    {

        parent::__construct($name);
        $this->setAttribute('method', 'post');

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type' => 'email',
                'id' => 'email',
                'class' => 'mdl-textfield__input',
            ),
            'options' => array(
                'label' => 'Email',
                'label_attributes' => array(
                    'class' => 'mdl-textfield__label',
                ),
                'id' => 'email',
                'placeholder' => 'example@example.com',
            )
        ));

        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type' => 'password',
                'id' => 'password',
                'class' => 'mdl-textfield__input',
            ),
            'options' => array(
                'label' => 'Mot de Passe',
                'label_attributes' => array(
                    'class' => 'mdl-textfield__label',
                ),
                'id' => 'password',
                'placeholder' => '***********',
            )
        ));

        /*
        $this->add(array(
            'name' => 'password_confirm',
            'attributes' => array(
                'type'  => 'password',
                'id'    => 'password_confirm',
                'class' => 'mdl-textfield__input',
            ),
            'options' => array(
                'label' => 'Confirmer Mot de passe',
                'label_attributes' => array(
                    'class' => 'mdl-textfield__label',
                ),
                'id' => 'password_confirm',
                'placeholder' => '***********',
            )
        ));
        */


        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'signupCsrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 3600
                )
            )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Submit',
                'class' => 'mdl-button mdl-button--accent mdl-js-button mdl-js-ripple-effect',
            ),
        ));
    }
}