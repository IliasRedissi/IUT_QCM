<?php

namespace QCM\Model;

use QCM\Model\Answer;
use Zend\Db\TableGateway\TableGateway;

class AnswerTable
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

    public function getAnswer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('idAnswer' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getAnswerByQuestionId($idQuestion)
    {
        $idQuestion  = (int) $idQuestion;
        $rowset = $this->tableGateway->select(array('idQuestion' => $idQuestion));
        return $rowset;
    }

    public function saveAnswer(Answer $answer)
    {
        $data = array(
            'title' => $answer->title,
            'idQuestion' => $answer->idQuestion,
        );

        $id = (int) $answer->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAnswer($id)) {
                $this->tableGateway->update($data, array('idAnswer' => $id));
            } else {
                throw new \Exception('Answer id does not exist');
            }
        }
    }

    public function deleteAnswer($id)
    {
        $this->tableGateway->delete(array('idAnswer' => (int) $id));
    }
}