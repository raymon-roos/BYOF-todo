<?php

declare(strict_types=1);

namespace controller;

class ErrorController extends ParentController
{
    public function renderErrorMessage(): void
    {
        $this->viewService->displayError($_SESSION['errors']);
    }

    public function internalServerError(): void
    {
        ob_clean();
        http_response_code(500);
        echo(file_get_contents('views/header.html'));
        echo(file_get_contents('views/500.html'));
        exit();
    }

    public function GETPageUnknown($target): void
    {
        http_response_code(404);
        $this->viewService->displayError(http_response_code() . " - $target not found");
    }

    public function GETObjectNotFound(): void
    {
        $this->viewService->displayError('Sorry, but the object you were searching for could not be found, try something else.');
    }

    public function GETInputIncorrect(): void
    {
        $this->viewService->displayWarning('Sorry, but the given input could not be processed, please try something else');
    }
}
