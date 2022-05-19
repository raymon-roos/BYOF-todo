<?php

session_start();

require_once '../vendor/autoload.php';

// (new \service\ViewService())->displayPage('header', []);

$router = new \controller\RouterController();
