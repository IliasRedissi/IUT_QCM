<?php

namespace QCM\Model;

use QCM\Model\Question;
use Zend\Db\TableGateway\TableGateway;

class QuestionTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getQuestion($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('idQuestion' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveQuestion(Question $question)
    {
        $data = array(
            'idUser' => $question->user,
            'title'  => $question->title
        );

        $id = (int) $question->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getQuestion($id)) {
                $this->tableGateway->update($data, array('idQuestion' => $id));
            } else {
                throw new \Exception('User id does not exist');
            }
        }
    }

    public function deleteQuestion($id)
    {
        $this->tableGateway->delete(array('idQuestion' => (int) $id));
    }
}