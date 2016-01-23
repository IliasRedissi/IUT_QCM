<?php


namespace QCM\Controller;


use Auth\Model\User;
use QCM\Form\AnswerForm;
use QCM\Model\Answer;
use QCM\Model\AnswerTable;
use QCM\Model\QuestionTable;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use QCM\Model\Question;
use QCM\Form\QuestionForm;

class AnswerController extends AbstractActionController
{
    protected $questionTable;
    protected $answerTable;
    protected $userAnswerTable;

    public function indexAction()
    {
        $idQuestion = (int) $this->params()->fromRoute('idQuestion', 0);
        if (!$idQuestion) {
            return $this->redirect()->toRoute('qcm', array(
                'action' => 'add'
            ));
        }
        return new ViewModel(array(
            'answers' => $this->getAnswerTable()->getAnswerByQuestionId($idQuestion),
            'idQuestion' => $idQuestion,
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

    public function addAction()
    {
        $idQuestion = (int) $this->params()->fromRoute('idQuestion', 0);
        if (!$idQuestion) {
            return $this->redirect()->toRoute('qcm', array(
                'action' => 'add',
            ));
        }
        $form = new AnswerForm();
        $form->get('submit')->setValue('Over');

        $request = $this->getRequest();
        /** @var Request $request */
        if ($request->isPost()) {
            $answer = new Answer();
            $form->setInputFilter($answer->getInputFilter());
            $form->setData($request->getPost());
            var_dump($form->isValid());
            if ($form->isValid()) {
                $answer->exchangeArray($form->getData());

                $answer->idQuestion = $idQuestion;
                
                $this->getAnswerTable()->saveAnswer($answer);

                return $this->redirect()->toRoute('answer', array(
                    'idQuestion' => $idQuestion,
                ));
            }
        }
        return array(
            'form' => $form,
            'idQuestion' => $idQuestion,
            );
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('qcm', array(
                'action' => 'add'
            ));
        }

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
        $idQuestion = (int) $this->params()->fromRoute('idQuestion', 0);
        $id = (int)$this->params()->fromRoute('id', 0);
        if (!$idQuestion || !$id) {
            return $this->redirect()->toRoute('qcm', array(
                'action' => 'add',
            ));
        }

        $request = $this->getRequest();
        /** @var Request $request */
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int)$request->getPost('id');
                $this->getAnswerTable()->deleteAnswer($id);
            }

            return $this->redirect()->toRoute('answer', array(
                'idQuestion' => $idQuestion,
            ));
        }

        return array(
            'id' => $id,
            'idQuestion' => $idQuestion,
            'answer' => $this->getAnswerTable()->getAnswer($id)
        );
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
     * @return array|object
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