<?php
namespace QCM\Form;

use Zend\Form\Form;

 class AnswerForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('question');

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'idQuestion',
             'type' => 'Hidden',
         ));
         $this->add(array(
             'name' => 'title',
             'attributes' => array(
                 'type'  => 'Text',
                 'id'    => 'title',
                 'class' => 'mdl-textfield__input',
             ),
             'options' => array(
                 'label' => 'Ask your answer',
                 'label_attributes' => array(
                     'class' => 'mdl-textfield__label',
                 ),
                 'id' => 'title',
             ),
         ));
         $this->add(array(
             'name' => 'add',
             'attributes' => array(
                 'type' => 'submit',
                 'value' => 'Add',
                 'id' => 'add',
                 'class' => 'mdl-button mdl-button mdl-js-button mdl-js-ripple-effect',
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'attributes' => array(
                 'type' => 'submit',
                 'value' => 'Go',
                 'id' => 'submit',
                 'class' => 'mdl-button mdl-button--accent mdl-js-button mdl-js-ripple-effect',
             ),
         ));
     }
 }