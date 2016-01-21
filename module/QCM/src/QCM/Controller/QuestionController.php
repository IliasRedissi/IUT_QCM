<?php


namespace Auth\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use QCM\Model\Question;
use QCM\Form\QuestionForm;

class QuestionController extends AbstractActionController
{
    protected $questionTable;
    protected $answerTable;
    protected $userAnswerTable;

    public function indexAction()
    {
        return new ViewModel(array(
            'questions' => $this->getQuestionTable()->fetchAll(),
        ));
    }

    public function getQuestionTable()
    {
        if (!$this->questionTable) {
            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('QCM\Model\QuestionTable');
        }
        return $this->questionTable;
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
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('question');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->getQuestionTable()->deleteQuestion($id);
            }

            // Redirect to list of questions
            return $this->redirect()->toRoute('question');
        }

        return array(
            'id' => $id,
            'question' => $this->getQuestionTable()->getQuestion($id)
        );
    }

    public function showResultAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('question');
        }


        $question = $this->getQuestionTable()->getQuestion($id);
        $answers = $this->getAnswerTable()->fetchAll();
        $userAnswers = $this->getUserAnswerTable()->fetchAll();


        return new ViewModel(array(
            'question' => $question,
            'answers' => $answers,
            'userAnswers' => $userAnswers
        ));
    }

    public function getAnswerTable()
    {
        if (!$this->answerTable) {
            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('QCM\Model\AnswerTable');
        }
        return $this->answerTable;
    }

    public function getUserAnswerTable()
    {
        if (!$this->userAnswerTable) {
            $sm = $this->getServiceLocator();
            $this->userAnswerTable = $sm->get('QCM\Model\UserAnswerTable');
        }
        return $this->userAnswerTable;
    }
}