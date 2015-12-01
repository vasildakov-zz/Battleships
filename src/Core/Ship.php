<?php
namespace Battleships\Core;

use Battleships\Core\Point;
use Battleships\Exceptions\InvalidArgumentException;

/**
 * Ship
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class Ship
{
    /**
     * @var array $positions
     */
    private $positions = [];

    /**
     * @var int $size
     */
    private $size;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var int $shots
     */
    private $shots = 0;

    /**
     * @param   string  $name [description]
     * @param   int     $size [description]
     */
    public function __construct($name, $size)
    {
        if (!isset($name) || !is_int($size)) {
            throw new InvalidArgumentException;
        }

        $this->name = $name;
        $this->size = $size;
    }

    /**
     * @return int $size [description]
     */
    public function getSize()
    {
        return $this->size;
    }
    
    /**
     * @param Point $point [description]
     */
    public function setPoint(Point $point)
    {
        if (count($this->positions) < $this->size) {
            $this->positions[] = $point;
        }
    }

    /**
     * @param  Point  $point [description]
     * @return [type]        [description]
     */
    public function checkPoint(Point $point)
    {
        return array_search($point, $this->positions) !== false;
    }

    /**
     * @param  Point  $point    [description]
     * @return int    $shots    [description]
     */
    public function hit(Point $point)
    {
        $this->shots += 1;
    }
    
    public function isSunk()
    {
        return $this->shots == $this->size;
    }
}
