<?php
namespace Battleships\Core;

use Battleships\Core\Ship;
use Battleships\Core\Point;

/**
 * ShipTest
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class ShipTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {

    }

    /**
     * @expectedException \Battleships\Exceptions\InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Ship(null, null);
    }


    public function testCanSetPoints()
    {
        $ship = new Ship('Battleship', 4);
        $ship->setPoint(new Point(3, 5));

        $reflectionClass = new \ReflectionClass($ship);
        $reflectionProperty = $reflectionClass->getProperty('positions');
        $reflectionProperty->setAccessible(true);
        
        $point = $reflectionProperty->getValue($ship)[0];
        $this->assertInstanceOf('Battleships\Core\Point', $point);

        $this->assertEquals(3, $point->getRow());
        $this->assertEquals(5, $point->getColumn());
    }


    public function testCheckPoint()
    {
        $ship = new Ship('Destroyer', 3);
        $point = new Point(5, 7);

        $this->assertFalse($ship->checkPoint($point));

        $ship->setPoint($point);

        $this->assertTrue($ship->checkPoint($point));
    }
}
