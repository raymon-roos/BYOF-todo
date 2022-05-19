<?php

namespace service;

use Twig\Environment as Twig;
use Twig\Error\Error as TwigError;

class ViewService
{
    protected Twig $twig;

    public function __construct()
    {
        $this->twig = $this->startTwig();
    }

    private function startTwig() 
    {
        try {
            $loader = new \Twig\Loader\FilesystemLoader('./views');
            $twig = new \Twig\Environment($loader);
            return $twig;
        } catch (TwigError $err) {
            $this->displayError("Twig failed to initialise " . $err->getMessage());
        } catch (\Exception $exc) {
            $this->displayError("An error occured, please try again later " . $exc->getMessage());
        }
    }

    public function displayPage(string $view, array $data = [])
    {
        try {
            echo $this->twig->render("$view.html", $data);
        } catch (TwigError $err) {
            $this->displayError("Twig encountered an error loading this page " . $err->getMessage());
        } catch (\Exception $exc) {
            $this->displayError("An error occured, please try again later " . $exc->getMessage());
        }
    }

    public function displayError(string $error)
    {
        $this->twig->render('error.html', [ 'error' => $error ]);
    }

    public function displayWarning(string $warning)
    {
        $this->twig->render('warning.html', [ 'warning' => $warning ]);
    }
}