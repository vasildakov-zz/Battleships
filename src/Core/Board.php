<?php
namespace Battleships\Core;

use Battleships\Core\Ship;
use Battleships\Core\Point;
use Battleships\Exceptions\InvalidArgumentException;

/**
 * Board
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class Board
{
    /**
     * @var int  $gridSize
     */
    private $gridSize;

    /**
     * @var array  $ships
     */
    private $ships = [];

    /**
     * @var array  $busySpots
     */
    private $busySpots = [];

    /**
     * @var array  $shots
     */
    public $shots;

    /**
     * @param int $gridSize [description]
     */
    public function __construct($gridSize)
    {
        if (!is_numeric($gridSize) || is_null($gridSize)) {
            throw new InvalidArgumentException;
        }

        $this->gridSize = $gridSize;
        $this->shots = [
                'hit'     => [],
                'miss'    => [],
                'counter' => 0
        ];
    }

    /**
     * Add ship to the board.
     * @param Ship $ship
     */
    public function addShip(Ship $ship)
    {
        $availablePositions = $this->getAvailableSpots($ship->getSize());
       
        $direction = array_rand($availablePositions);

        $startPointIndex = array_rand($availablePositions[$direction]);
        $startPoint = $availablePositions[$direction][$startPointIndex];

        $this->placeShip($ship, $startPoint, $direction);
    }

    /**
     * Scan the board for available starting positions for ship.
     * @param  int $shipSize
     * @return Array
     */
    public function getAvailableSpots($shipSize)
    {
        $available = [
            'horizontal'    => [],
            'vertical'      => [],
        ];

        for ($i = 0; $i < $this->gridSize; $i++) {
            for ($j = 0; $j < $this->gridSize; $j++) {
                $currentPoint = new Point($i, $j);
                if ($this->freeSpot($currentPoint)) {
                    if ($this->shipFitsHorizontally($currentPoint, $shipSize)) {
                        $available['horizontal'][] = $currentPoint;
                    }
                    if ($this->shipFitsVertically($currentPoint, $shipSize)) {
                        $available['vertical'][] = $currentPoint;
                    }
                } else {
                    continue;
                }
            }
        }
        return $available;
    }

    /**
     * Checks if ship can be placed horizontally.
     * @param  Point  $point    Starting point for the ship
     * @param  int    $shipSize The size of the ship
     * @return bool
     */
    private function shipFitsHorizontally(Point $point, $shipSize)
    {
        if (($point->getColumn() + $shipSize) <= $this->gridSize) {
            for ($k=$point->getColumn(); $k < $point->getColumn() + $shipSize; $k++) {
                if (! $this->freeSpot(new Point($point->getRow(), $k))) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Checks if ship can be placed vertically.
     * @param  Point  $point Starting point for the ship
     * @param  int $shipSize The size of the ship
     * @return bool
     */
    private function shipFitsVertically(Point $point, $shipSize)
    {
        if (($point->getRow() + $shipSize) <= $this->gridSize) {
            for ($k = $point->getRow(); $k < $point->getRow() + $shipSize; $k++) {
                if (!$this->freeSpot(new Point($k, $point->getColumn()))) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Helper function placing the ship on the board.
     * @param  Ship   $ship
     * @param  Point  $startPoint
     * @param  String $direction
     */
    private function placeShip(Ship $ship, Point $startPoint, $direction)
    {
        if ($direction == 'horizontal') {
            $end = $startPoint->getColumn() + $ship->getSize() - 1;
            $row = $startPoint->getRow();

            foreach (range($startPoint->getColumn(), $end) as $column) {
                $point = new Point($row, $column);
                $ship->setPoint($point);
                $this->busySpots[] = $point;
            }

        } else {
            $end = $startPoint->getRow() + $ship->getSize() - 1;
            $column = $startPoint->getColumn();
            
            foreach (range($startPoint->getRow(), $end) as $row) {
                $point = new Point($row, $column);
                $ship->setPoint($point);
                $this->busySpots[] = $point;
            }
        }
        $this->ships[] = $ship;
    }

    /**
     * Check if all ships on board are sunk.
     * @return bool
     */
    public function allShipsSunk()
    {
        foreach ($this->ships as $ship) {
            if (!$ship->isSunk()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if the provided position is taken by a ship.
     * @param  Point  $point
     * @return bool
     */
    public function freeSpot(Point $point)
    {
        return !in_array($point, $this->busySpots);
    }

    public function getSize()
    {
        return $this->gridSize;
    }

    public function getShips()
    {
        return $this->ships;
    }
}
