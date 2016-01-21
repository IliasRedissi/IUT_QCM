<?php


namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use QCM\Model\Question;
use QCM\Form\QuestionForm;

class QuestionController extends AbstractActionController
{
    protected $questionTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'questions' => $this->getQuestionTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        $form = new QuestionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        /** @var Zend\RequestInterface $request */
        if ($request->isPost()) {
            $question = new Question();
            $form->setInputFilter($question->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $question->exchangeArray($form->getData());
                $this->getQuestionTable()->saveQuestion($question);

                // Redirect to list of question
                return $this->redirect()->toRoute('question');
            }
        }
        return array('form' => $form);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('question');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getQuestionTable()->deleteQuestion($id);
            }

            // Redirect to list of questions
            return $this->redirect()->toRoute('question');
        }

        return array(
            'id'    => $id,
            'question' => $this->getQuestionTable()->getQuestion($id)
        );
    }

    public function getQuestionTable()
    {
        if (!$this->questionTable) {
            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('QCM\Model\QuestionTable');
        }
        return $this->questionTable;
    }
}