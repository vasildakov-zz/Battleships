<?php
namespace Battleships\Core;

use Battleships\Exceptions\InvalidArgumentException;

/**
 * Point
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class Point
{
    /**
     * @var string $row
     */
    private $row;

    /**
     * @var int $column
     */
    private $column;

    /**
     * @param int $row    [description]
     * @param int $column [description]
     */
    public function __construct($row, $column)
    {
        if (!is_numeric($row) || !is_numeric($column)) {
            throw new InvalidArgumentException;
        }

        $this->row      = $row;
        $this->column   = $column;
    }

    /**
     * @return [type] [description]
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return [type] [description]
     */
    public function getColumn()
    {
        return $this->column;
    }
}
