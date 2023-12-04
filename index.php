<?php
session_start();
require_once __DIR__."/vendor/autoload.php";
require_once __DIR__."/Config/Config.php";
$dotenv=Dotenv\Dotenv::createImmutable(__DIR__); //para acceder al archivo .env
$dotenv->safeLoad();

use Controllers\FrontController;
FrontController::main();