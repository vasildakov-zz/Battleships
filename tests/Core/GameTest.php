<?php
namespace Battleships\Core;

use Battleships\Core\Game;
use Battleships\Core\Board;
use Battleships\Core\Renderer\SimpleBoardRenderer;

/**
 * GameTest
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class GameTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        @session_start();
    }

    public function setUp()
    {
        $board = new Board(10);
        $_SESSION['board'] = $board;
    }

    /**
     * @expectedException \Battleships\Exceptions\InvalidArgumentException
     */
    public function testExceptionIsRaisedForInvalidConstructorArgument()
    {
        new Game(null, new SimpleBoardRenderer());
    }


    public function testGetSessionData()
    {
        $game = new Game(10, new SimpleBoardRenderer());

        $class = new \ReflectionClass($game);
        $method = $class->getMethod('getSessionData');
        $method->setAccessible(true);
        
        $this->assertTrue($method->invokeArgs($game, []));
    }


    public function testCanPlay()
    {
        $game = new Game(10, new SimpleBoardRenderer());
        $game->play();
    }


    public function testIsGameOver()
    {
        $game = new Game(10, new SimpleBoardRenderer());
        $this->assertFalse($game->isGameOver());
    }

    public function testEndGame()
    {
        $game = new Game(10, new SimpleBoardRenderer());
        $game->endGame();
    }
}
