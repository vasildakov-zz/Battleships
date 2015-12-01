<?php
require __DIR__ . '/vendor/autoload.php';

use Battleships\Controller\Controller;

session_start();
$gridSize = 10;
$game = new Controller($gridSize);

$game->start();
