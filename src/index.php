<?php

session_start();

require_once '../vendor/autoload.php';

$router = (new \controller\RouterController())->chooseController();
