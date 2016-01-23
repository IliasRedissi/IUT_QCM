<?php

namespace QCM;

use QCM\Model\Answer;
use QCM\Model\AnswerTable;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use QCM\Model\Question;
use QCM\Model\QuestionTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'QCM\Model\QuestionTable' =>  function($sm) {
                    $tableGateway = $sm->get('QuestionTableGateway');
                    $table = new QuestionTable($tableGateway);
                    return $table;
                },
                'QuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Question());
                    return new TableGateway('question', $dbAdapter, null, $resultSetPrototype);
                },
                'QCM\Model\AnswerTable' =>  function($sm) {
                    $tableGateway = $sm->get('AnswerTableGateway');
                    $table = new AnswerTable($tableGateway);
                    return $table;
                },
                'AnswerTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Answer());
                    return new TableGateway('answer', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}