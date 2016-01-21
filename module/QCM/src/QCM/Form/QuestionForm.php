<?php
namespace QCM\Form;

use Zend\Form\Form;

 class QuestionForm extends Form
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
             'name' => 'title',
             'attributes' => array(
                 'type'  => 'Text',
                 'id'    => 'title',
                 'class' => 'mdl-textfield__input',
             ),
             'options' => array(
                 'label' => 'Ask your question',
                 'label_attributes' => array(
                     'class' => 'mdl-textfield__label',
                 ),
                 'id' => 'title',
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