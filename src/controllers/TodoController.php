<?php

namespace controller;

use PDOException;
use RedBeanPHP\R as R;
use service\DatabaseConnectionService as dbCon;

class TodoController
{
    public function __construct()
    {
        (new dbCon())->connectDB();
    }

    public function GETCreateTodo()
    {
        $error = isset($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "";
        global $twig;
        echo $twig->render(
            'create_todo.html',
            ['error' => $error]
        );
        unset($_SESSION['errorMessage']);
    }

    public function GETViewLists()
    {
        $lists = $this->queryAllLists();
        if ($lists) {
            global $twig;
            echo $twig->render(
                'view_lists.html',
                ['lists' => $lists]
            );
            return;
        }
        (new ErrorController())->GETObjectNotFound();
    }

    public function queryAllLists()
    {
        $lists = R::findAll('lists');
        if ($lists) {
            return $lists;            
        }
        return false;
    }

    public function POSTCreateTodo()
    {
        if (!empty($_POST['listName']) && !empty($_POST['todo1'])) {
                $this->addTodo($_POST);
        } else {
            $_SESSION['errorMessage'] = 'Missing list name or todo item';
        }

        $this->GETCreateTodo();
    }

    private function findList($listName)
    {
        $list = R::findOne('lists', 'listName = ?', [ $listName ]);
        if ($list) {
            return $list;
        }
        return false;
    }

    private function updateList($listName)
    {
        $listID = R::findOne('lists', 'listName = ?', [ $listName ])['id'];
        if ($listID) {
            var_dump($listID);
            return true;
        }
        return false;
    }

    private function addList($newListName)
    {
        $newTodoList = R::dispense('lists');                
        $newTodoList['listName'] = $newListName;
        R::store($newTodoList, true);
    }

    private function addTodo($formInput)
    {
        R::debug();
        try {
            $newTodo = R::dispense('todos');                
            $newTodo['todoContent'] = $formInput['todo1'];
            $newTodo['done'] = false;
            $newTodo['list'] = R::find('lists', 'listName = ?', [$formInput['listName']]);
            R::store($newTodo, true);
        } catch (PDOException $e) {
            var_dump($e->getMessage());
        }
    }
}