<?php

namespace controller;

use RedBeanPHP\R as R;
use service\UserService;

class TodoController extends \service\ProviderService
{
    public function GETCreateTodo()
    {
        $error = !empty($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "";
        echo $this->twig->render(
            'create_todo.html',
            ['error' => $error]
        );
        unset($_SESSION['errorMessage']);
    }

    public function GETViewLists()
    {
        $lists = R::findAll('lists');
        if ($lists) {
            echo $this->twig->render(
                'view_lists.html',
                ['lists' => $lists]
            );
            return;
        }
        (new ErrorController())->GETObjectNotFound();
    }

    public function selectByID($id)
    {
        $list = $this->findListById($id);
        $todos = R::findAll('todos', 'list_id = ?', [$id]);

        if ($list && $todos) {
            $error = !empty($_SESSION['errorMessage']) ? $_SESSION['errorMessage'] : "";
            echo $this->twig->render(
                'list_details.html',
                ['list' => $list,
                'todos' => $todos,
                'error' => $error]
            );
            unset($_SESSION['errorMessage']);
            return;
        }

        (new ErrorController())->GETObjectNotFound();
    }

    public function POSTCreateTodo()
    {
        if (!empty($_POST['listName']) && !empty($_POST['todo1'])) {
            if (!$this->findList($_POST['listName'])) {
                $this->addList($_POST['listName']);
            }
            $this->addTodo($_POST);
            $this->GETViewLists();
            return;
        }
        $_SESSION['errorMessage'] = 'Missing list name or todo item';
        $this->GETCreateTodo();
    }

    private function findListById($id)
    {
        $list = R::findOne('lists', 'id = ?', [ $id ]);

        return ($list) ?: false;
    }

    private function findList($listName)
    {
        $list = R::findOne('lists', 'name = ?', [ $listName ]);

        return ($list) ?: false;         
    }

    public function POSTUpdateList()
    {
        var_dump($_POST);
        if (!empty($_POST)) {
            foreach ($_POST as $formInput) {
                if ($formInput != "Update tasks") {
                    $newData[] = $formInput;
                }
            }
        }
        var_dump($newData);
        $_SESSION['errorMessage'] = 'Missing list name or todo item';
    }

    private function addList($newListName)
    {
        $newTodoList = R::dispense('lists');                
        $newTodoList->name = $newListName;
        $newTodoList->user = (new UserService())->findLoggedInUserBySession();
        R::store($newTodoList);
    }

    private function addTodo($formInput)
    {
        try {
            $newTodo = R::dispense('todos');                
            $newTodo->task = $formInput['todo1'];
            $newTodo->done = 'no';
            $newTodo->list = R::findOne('lists', 'name = ?', [$formInput['listName']]);
            $newTodo->user = (new UserService())->findLoggedInUserBySession();
            R::store($newTodo);
        } catch (\RedBeanPHP\RedException $e) {
            (new ErrorController())->GETDebug($e->getMessage());
        }
    }
}