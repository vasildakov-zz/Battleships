<?php
namespace Battleships\Core\Renderer;

use Battleships\Core\Board;
use Battleships\Core\Point;
use Battleships\Core\Renderer\BoardRendererInterface;

/**
 * SimpleBoardRenderer
 *
 * Implements simple rendering of the board using only spaces and new lines.
 *
 * @package    Battleships
 * @author     Vasil Dakov <vasildakov@gmail.com>
 */
class SimpleBoardRenderer implements BoardRendererInterface
{
    /**
     * @var Battleships\Core\Board $board
     */
    private $board;

    /**
     * @var boolean $cheatMode
     */
    private $cheatMode;

    /**
     * Draw the game board.
     *
     * @return string Board output as a string
     */
    public function renderGrid(Board $board, $cheatMode)
    {
        $this->board = $board;
        $letterMap = range('A', 'Z');
        $boardOutput = '   ';
        for ($i = 1; $i <= $this->board->getSize(); $i++) {
            $boardOutput .= str_pad($i, 3, " ", STR_PAD_BOTH);
        }
        $boardOutput .= "\n";

        for ($row = 0; $row < $this->board->getSize(); $row++) {
            for ($column = 0; $column < $this->board->getSize(); $column++) {
                if ($column == 0) {
                    $boardOutput .= " {$letterMap[$row]} ";
                }
                $point = new Point($row, $column);
                $boardOutput .= str_pad($this->drawSector($point, $cheatMode), 3, " ", STR_PAD_BOTH);
            }
            $boardOutput .= "\n";
        }
        return $boardOutput;
    }
    
    /**
     * Helper function to draw a single spot on the board.
     * @param  Point $point the point to draw
     *
     * @return string String containing the output for this spot (X, -, .)
     */
    private function drawSector(Point $point, $cheatMode)
    {
        if ($cheatMode) {
            if (! $this->board->freeSpot($point)) {
                return ' X ';
            }
            return '   ';
        }

        if (in_array($point, $this->board->shots['hit'])) {
            return " X ";
        }
        if (in_array($point, $this->board->shots['miss'])) {
            return " - ";
        }

        return " . ";
    }
}
