<?php
namespace Battleships\Controller;

/**
 * ControllerInterface
 *
 * @package    Battleships
 * @author     Vasil Dakov <vasildakov@gmail.com>
 */
interface ControllerInterface
{
    public function start();
    public function getUserInput();
}
