<?php

namespace controller;

class ErrorController extends ParentController
{
    public function renderErrorMessage()
    {
        $this->viewService->displayError($_SESSION['errors']);
    }

    public function GETPageUnknown($target)
    {
        http_response_code(404);
        $this->viewService->displayError(http_response_code() . " - $target not found");
    }

    public function GETObjectNotFound()
    {
        $this->viewService->displayError('Sorry, but the object you were searching for could not be found, try something else.');
    }

    public function GETInputIncorrect()
    {
        $this->viewService->displayWarning('Sorry, but the object you were searching for could not be found, try something else.');
    }

    public function GETDebug($dump)
    {
        $this->viewService->displayWarning($dump);
    }
}