<?php
namespace Battleships\Core;

use Battleships\Core\Board;
use Battleships\Core\Point;
use Battleships\Core\Renderer\BoardRendererInterface;
use Battleships\Core\Ship;
use Battleships\Exceptions\InvalidArgumentException;

/**
 * Game
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class Game
{

    private $userInput;

    /**
     * Game output data.
     * @var array
     */
    private $outputData = [];

    /**
     * Game over flag.
     * @var boolean
     */
    private $gameOver = false;

    /**
     * Size of the grid.
     * @var int
     */
    private $gridSize;

    /**
     * @var \Battleships\Core\Board
     */
    private $board;

    /**
     * Renderer used to render the board.
     * @var \Battleships\Core\Renderers\BoardRendererInterface
     */
    private $renderer;

    /**
     * The result from the shot (Hit, Miss, Sunk, Error).
     * @var string
     */
    private $shotResult;

    /**
     * Cheat mode flag.
     * @var boolean
     */
    private $cheatMode = false;


    /**
     * @param int $gridSize [description]
     * @param BoardRendererInterface $renderer [description]
     */
    public function __construct($gridSize, BoardRendererInterface $renderer)
    {
        if (!is_numeric($gridSize) || is_null($renderer)) {
            throw new InvalidArgumentException;
        }

        $this->gridSize = $gridSize;
        $this->renderer = $renderer;
    }

    /**
     * Initiates game.
     */
    private function initGame()
    {
        $this->board = new Board($this->gridSize);

        $this->board->addShip(new Ship('Submarine', 3));
        $this->board->addShip(new Ship('Submarine', 3));

        $this->board->addShip(new Ship('Destroyer', 4));
        $this->board->addShip(new Ship('Destroyer', 4));

        $this->board->addShip(new Ship('Battleship', 5));
        $this->board->addShip(new Ship('Battleship', 5));
    }

    /**
     * Starts gameplay.
     */
    public function play()
    {
        if ($this->getSessionData()) {
            if (!empty($this->userInput)) {
                $this->makeShot();
                if ($this->board->allShipsSunk()) {
                    $this->endGame();
                }
            }
        } else {
            $this->initGame();
        }
        $this->setOutputData();
        $this->setSessionData();
        $this->cheatMode = false;
    }

    private function getSessionData()
    {
        if (isset($_SESSION['board'])) {
            $this->board = $_SESSION['board'];
            return true;
        } else {
            return false;
        }
    }

    private function setSessionData()
    {
        $_SESSION['board'] = $this->board;
    }


    private function makeShot()
    {
        try {
            $shotPoint = $this->processUserInput();
            
            $hit = false;
            foreach ($this->board->getShips() as $ship) {
                if ($ship->checkPoint($shotPoint)) {
                    $hit = true;
                    
                    if (! in_array($shotPoint, $this->board->shots['hit'])) {
                        $ship->hit($shotPoint);
                        $this->shotResult = "Hit";
                    }

                    if ($ship->isSunk()) {
                        $this->shotResult = "Sunk";
                    }
                    break;
                }
            }

            $this->board->shots['counter'] += 1;
            
            if ($hit) {
                $this->board->shots['hit'][] = $shotPoint;
            } else {
                $this->board->shots['miss'][] = $shotPoint;
                $this->shotResult = "Miss";
            }
        } catch (\Exception $e) {
            if (trim($this->userInput) == 'show') {
                $this->cheatMode = true;
            } else {
                $this->shotResult = 'Error';
            }
        }
    }

    public function endGame()
    {
        $this->gameOver = true;
        session_destroy();
    }
    
    public function isGameOver()
    {
        return $this->gameOver;
    }

    public function setUserInput($input)
    {
        $this->userInput = $input;
    }

    /**
     * Turns user input into Point on board.
     * @return Point
     * @throws Exception if wrong input was provided.
     */
    private function processUserInput()
    {
        if (preg_match("/[a-zA-Z][0-9]+/", $this->userInput) === 1) {
            preg_match("/[a-zA-Z]+/", $this->userInput, $row);
            preg_match("/[0-9]+/", $this->userInput, $column);
            
            $row = array_search(strtoupper($row['0']), range('A', 'Z'));
            if ($row === false) {
                throw new \Exception("Wrong user input.");
            }
            
            $column = $column['0'] - 1;
            if ($row > $this->gridSize - 1 || $column > $this->gridSize - 1) {
                throw new \Exception("Wrong user input.");
            }
            return new Point($row, $column);
        } else {
            throw new \Exception("Wrong user input.");
        }
    }

    public function setOutputData()
    {
        $this->outputData['grid']       = $this->renderer->renderGrid($this->board, $this->cheatMode);
        $this->outputData['shotResult'] = $this->shotResult;
        $this->outputData['gameOver']   = $this->gameOver;
        if ($this->gameOver) {
            $this->outputData['shotsToSuccess'] = $this->board->shots['counter'];
        }
    }

    public function getOutputData()
    {
        return $this->outputData;
    }
}
