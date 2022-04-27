<?php

namespace controller;

use RedBeanPHP\R as R;
use service\DatabaseConnectionService as dbCon;

class TodoController
{
    public function __construct()
    {
        (new dbCon())->connectDB();
    }

    public function GETTodo()
    {
        $error = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "";
        global $twig;
        echo $twig->render(
            'todo.html',
            ['error' => $error]
        );
        unset($_SESSION['errorMessage']);
    }

    public function POSTTodo()
    {
        var_dump($_POST);
        if (!empty($_POST['list_name']) && !empty($_POST['todo1'])) {
            if ($this->findList($_POST['list_name'])) {
                $this->addList($_POST);
            }
        } else {
            $_SESSION['errorMessage'] = 'Missing list name or todo item';
        }

        $this->GETTodo();
        unset($_SESSION['errorMessage']);
    }

    private function findList($listName)
    {
        $list = R::findOne('lists', 'list_name = ?', [ $listName ]);
        if ($list) {
            return true;
        }
        return false;
    }

    private function updateList()
    {
    }

    private function addList($newList)
    {
        $newTodoList = R::dispense('lists');                
        $newTodoList['list_name'] = $newList['list_name'];
        R::store($newTodoList, false);
    }

    private function addTodo($list)
    {
        $newTodo = R::dispense('todos');                
        $newTodo['list_name'] = $list['list_name'];
        $newTodo['todo'] = $input['todo1'];
        R::store($newTodo, false);
    }
}