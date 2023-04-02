<?php

namespace service;

use controller\ErrorController;
use Twig\Environment as Twig;
use Twig\Loader\FilesystemLoader as TwigLoader;

class ViewService
{
    private Twig $twig;

    public function __construct()
    {
        $this->twig = $this->startTwig();
    }

    private function startTwig(): Twig | false
    {
        try {
            $twig = new Twig(new TwigLoader('./views'));
            return $twig ?? false;
        } catch (\Throwable) {
            ErrorController::internalServerError();
        }
    }

    public function displayPage(string $view, array $data = []): void
    {
        $this->twig->render("$view.html", $data);
    }

    public function displayError(string $error): void
    {
        $this->twig->render('error.html', [ 'error' => $error ]);
    }

    public function displayWarning(string $warning): void
    {
        $this->twig->render('warning.html', [ 'warning' => $warning ]);
    }

    public function GETDebug(mixed $dump): void
    {
        if (is_string($dump)) {
            $this->twig->render('debug.html', [ 'debug' => $dump ]);
            return;
        }

        if (is_array($dump)) {
            $this->twig->render('debug.html', [ 'debug' => implode(" <br> " , $dump) ]);
            return;
        }

        echo "<code style=\"background-color=\"white\";\"><h2>{var_dump($dump)}</h2></code>";
    }
}
