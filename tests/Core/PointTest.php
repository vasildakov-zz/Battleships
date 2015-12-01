<?php
namespace Battleships\Core;

use Battleships\Core\Point;
use Battleships\Core\Renderer\SimpleBoardRenderer;

/**
 * PointTest
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class PointTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->row      = 3;
        $this->column   = 7;
    }

    /**
     * @expectedException \Battleships\Exceptions\InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Point(null, null);
    }


    public function testPointAccessors()
    {
        $point = new Point($this->row, $this->column);
        
        $this->assertInstanceOf('Battleships\Core\Point', $point);
        $this->assertEquals($this->row, $point->getRow());
        $this->assertEquals($this->column, $point->getColumn());
    }
}
