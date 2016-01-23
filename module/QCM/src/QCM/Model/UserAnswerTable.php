<?php

namespace QCM\Model;

use QCM\Model\Answer;
use Zend\Db\TableGateway\TableGateway;

class UserAnswerTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {

        $resultSet = $this->tableGateway->select();
        $resultSet->buffer();
        return $resultSet;
    }

    public function getByAnswer($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('idAnswer' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getAnswerByUserId($userId)
    {
        $userId  = (int) $userId;
        $rowset = $this->tableGateway->select(array('idUser' => $userId));
        return $rowset;
    }

    public function saveUserAnswer(UserAnswer $userAnswer)
    {
        $data = array(
            'idUser' => $userAnswer->idUser,
            'idAnswer' => $userAnswer->idAnswer,
        );
        $this->tableGateway->insert($data);
    }

    public function deleteAnswer($idAnswer, $idQuestion)
    {
        $this->tableGateway->delete(array('idAnswer' => (int) $idAnswer, 'idQuestion' => (int) $idQuestion));
    }
}