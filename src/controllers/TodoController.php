<?php

declare(strict_types=1);

namespace controller;

use RedBeanPHP\R as R;
use RedBeanPHP\OODBBean as Bean;
use service\UserService;

class TodoController extends ParentController
{
    public function GETCreateTodo(string $warning = ''): void
    {
        $this->viewService->displayPage('create_todo', ['warning' => $warning]); 
    }

    public function GETViewLists(): void
    {
        $lists = R::findAll('lists');
        if ($lists) {
            foreach ($lists as $key => $listBean) {
                $listPrios[$key] = strtolower($listBean->priority->name);
            }
            $this->viewService->displayPage('view_lists', ['lists' => $lists, 'listPrios' => $listPrios]);
            return;
        }
        (new ErrorController())->GETObjectNotFound();
    }

    public function selectByID($id): void
    {
        $list = $this->findListById($id);
        $todos = R::findAll('todos', 'list_id = ?', [$id]);

        if ($list && $todos) {
            $this->viewService->displayPage(
                'list_details', 
                ['list' => $list,
                'todos' => $todos]
            );
            return;
        }

        (new ErrorController())->GETObjectNotFound();
    }

    public function POSTCreateTodo(): void
    {
        if (!empty($_POST['listName']) && !empty($_POST['todo1'])) {
            if (!$this->findList($_POST['listName'])) {
                $this->addList($_POST['listName']);
            }
            $this->addTodo($_POST);
            $this->GETViewLists();
            return;
        }
        $this->GETCreateTodo('Missing list name or todo item');
    }

    private function findListById(int $id): Bean | false
    {
        $list = R::findOne('lists', 'id = ?', [ $id ]);

        return ($list) ?: false;
    }

    private function findList(string $listName): Bean | false
    {
        $list = R::findOne('lists', 'name = ?', [ $listName ]);

        return ($list) ?: false;         
    }

    public function POSTUpdateList(): void
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
    }

    private function addList(string $newListName): void
    {
        $newTodoList = R::dispense('lists');                
        $newTodoList->name = $newListName;
        $newTodoList->user = (new UserService())->findLoggedInUserBySession();
        $newTodoList->priority = R::enum('priority:medium');
        R::store($newTodoList);
    }

    private function addTodo(array $formInput): void
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
