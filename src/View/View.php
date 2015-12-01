<?php
namespace Battleships\View;

/**
 * View
 *
 * @package Battleships
 * @author Vasil Dakov <vasildakov@gmail.com>
 */
class View implements ViewInterface
{
    const TEMPLATE = './src/Template/battleships.html';

    private $data = [];

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    public function render($data = [])
    {
        if (!empty($data)) {
            $this->data = $data;
        }

        extract($this->data);

        ob_start();
        include(self::TEMPLATE);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
