<?php
namespace Battleships\Core;

use Battleships\Core\Board;
use Battleships\Core\Ship;

/**
 * BoardTest
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class BoardTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Battleships\Exceptions\InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Board(null);
    }

    public function testCanAddShipsToBoard()
    {
        $board = new Board(10);
        $this->assertEmpty($board->getShips());

        $board->addShip(new Ship('Submarine', 3));
        $board->addShip(new Ship('Destroyer', 4));
        $board->addShip(new Ship('Battleship', 5));

        $this->assertEquals(3, count($board->getShips()));
    }

    public function testAllShipsSunk()
    {
        $board = new Board(10);
        $this->assertEmpty($board->getShips());

        $board->addShip(new Ship('Submarine', 3));

        $this->assertFalse($board->allShipsSunk());
    }
}
