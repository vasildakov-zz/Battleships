<?php
namespace Battleships\Core\Renderer;

use Battleships\Core\Board;

/**
 * BoardRendererInterface
 *
 * @package    Battleships
 * @author     Vasil Dakov <vasildakov@gmail.com>
 */
interface BoardRendererInterface
{
    public function renderGrid(Board $board, $cheatMode);
}
