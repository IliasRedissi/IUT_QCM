<?php


namespace QCM\Controller;


use Auth\Model\User;
use QCM\Model\AnswerTable;
use QCM\Model\QuestionTable;
use QCM\Model\UserAnswer;
use QCM\Model\UserAnswerTable;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
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

    public function addAction()
    {
        $form = new QuestionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        /** @var Request $request */
        if ($request->isPost()) {
            $question = new Question();
            $form->setInputFilter($question->getInputFilter());
            $form->setData($request->getPost());



            if ($form->isValid()) {
                $question->exchangeArray($form->getData());

                $session = new Container('User');
                $userTable = $this->getServiceLocator()->get('Auth\Model\UserTable');
                /** @var User $user */
                $user = $userTable->getUserByEmail($session->offsetGet('email'));
                $question->user = $user->id;

                $this->getQuestionTable()->saveQuestion($question);

                // Redirect to list of question
                return $this->redirect()->toRoute('qcm');
            }
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('qcm', array(
                'action' => 'add'
            ));
        }

        // Get the Album with the specified id.  An exception is thrown
        // if it cannot be found, in which case go to the index page.
        try {
            /** @var Question $question */
            $question = $this->getQuestionTable()->getQuestion($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('qcm', array(
                'action' => 'index'
            ));
        }

        $form  = new QuestionForm();
        $form->bind($question);
        $form->get('submit')->setAttribute('value', 'Edit');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($question->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $session = new Container('User');
                $userTable = $this->getServiceLocator()->get('Auth\Model\UserTable');
                /** @var User $user */
                $user = $userTable->getUserByEmail($session->offsetGet('email'));
                $question->user = $user->id;
                $question->id = $id;
                $this->getQuestionTable()->saveQuestion($question);

                return $this->redirect()->toRoute('qcm');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('qcm');
        }

        $request = $this->getRequest();
        /** @var Request $request */
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->getQuestionTable()->deleteQuestion($id);
            }

            // Redirect to list of questions
            return $this->redirect()->toRoute('qcm');
        }

        return array(
            'id' => $id,
            'question' => $this->getQuestionTable()->getQuestion($id)
        );
    }

    public function answerAction(){
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('qcm');
        }

        $answers = $this->getAnswerTable()->getAnswerByQuestionId($id);

        $request = $this->getRequest();
        /** @var Request $request */
        if ($request->isPost()) {
            $idAnswer = $request->getPost('options', null);
            $userAnswer = new UserAnswer();
            $userAnswer->idAnswer = $idAnswer;

            $session = new Container('User');
            $userTable = $this->getServiceLocator()->get('Auth\Model\UserTable');
            /** @var User $user */
            $user = $userTable->getUserByEmail($session->offsetGet('email'));
            $userAnswer->idUser = $user->id;

            $this->getUserAnswerTable()->saveUserAnswer($userAnswer);

            // Redirect to list of questions
            return $this->redirect()->toRoute('qcm');
        }

        return array(
            'question' => $this->getQuestionTable()->getQuestion($id),
            'answers' => $answers,
        );
    }

    public function resultAction()
    {
        $id = (int)$this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('qcm');
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

    /**
     * @return QuestionTable
     */
    public function getQuestionTable()
    {
        if (!$this->questionTable) {
            $sm = $this->getServiceLocator();
            $this->questionTable = $sm->get('QCM\Model\QuestionTable');
        }
        return $this->questionTable;
    }

    /**
     * @return AnswerTable
     */
    public function getAnswerTable()
    {
        if (!$this->answerTable) {
            $sm = $this->getServiceLocator();
            $this->answerTable = $sm->get('QCM\Model\AnswerTable');
        }
        return $this->answerTable;
    }

    /**
     * @return UserAnswerTable
     */
    public function getUserAnswerTable()
    {
        if (!$this->userAnswerTable) {
            $sm = $this->getServiceLocator();
            $this->userAnswerTable = $sm->get('QCM\Model\UserAnswerTable');
        }
        return $this->userAnswerTable;
    }
}