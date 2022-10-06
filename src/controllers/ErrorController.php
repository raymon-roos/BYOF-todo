<?php

declare(strict_types=1);

namespace controller;

class ErrorController extends ParentController
{
    public function renderErrorMessage(): void
    {
        $this->viewService->displayError($_SESSION['errors']);
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
        $this->viewService->displayWarning('Sorry, but the object you were searching for could not be found, try something else.');
    }

    public function GETDebug(mixed $dump): void
    {
        $this->viewService->displayWarning($dump);
    }
}
