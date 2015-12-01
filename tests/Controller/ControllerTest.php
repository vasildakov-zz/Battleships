<?php
namespace Battleships\Controller;

use Battleships\Controller\Controller;

/**
 * ControllerTest
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Battleships\Exceptions\InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Controller(null);
    }

    /**
     * @expectedException \Battleships\Exceptions\InvalidArgumentException
     */
    public function testCannotBeConstructedFromNonIntegerValue()
    {
        new Controller("string");
    }


    public function testStart()
    {
        $controller = new Controller(10);
        $var = $controller->start();
        //var_dump($_SESSION['board']);
    }
}
