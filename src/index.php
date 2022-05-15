<?php

session_start();

require_once '../vendor/autoload.php';

echo (new \service\ProviderService())->twig->render('header.html');

$router = new \controller\RouterController();
