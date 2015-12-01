<?php
namespace Battleships\Controller;

use Battleships\Core\Game;
use Battleships\View\View;
use Battleships\Core\Renderer\SimpleBoardRenderer;
use Battleships\Controller\ControllerInterface;
use Battleships\Exceptions\InvalidArgumentException;

/**
 * Controller
 *
 * @package    Battleships
 * @author     Vasil Dakov <vasildakov@gmail.com>
 */
class Controller implements ControllerInterface
{
    /**
     * @var Battleships\Core\Game $game
     */
    private $game;

    /**
     * @var Battleships\View\View $view
     */
    private $view;

    /**
     * @param integer $gridSize
     */
    public function __construct($gridSize)
    {
        if (!is_numeric($gridSize) || is_null($gridSize)) {
            throw new InvalidArgumentException;
        }

        $this->game = new Game($gridSize, new SimpleBoardRenderer);
        $this->view = new View();
    }

    public function start()
    {
        $this->game->setUserInput($this->getUserInput());
        $this->game->play();
        echo $this->view->render($this->game->getOutputData());
    }

    public function getUserInput()
    {
        return isset($_POST['coord']) ? $_POST['coord'] : '';
    }
}
