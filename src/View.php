<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

/**
 * View class
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable
 */
class View
{
    private $view;

    private $viewType;

    /**
     * View constructor
     *
     * @param string $view View file
     * @param array $args Extra arguments
     * @param string $viewType View type
     */
    public function __construct(string $view, string $viewType = 'general')
    {
        $this->view = $view;
        $this->viewType = $viewType;
    }

    /**
     * Render view based on type
     *
     * @param array $args Arguments
     */
    public function render(array $args = [])
    {
        $path = dirname(__FILE__);

        if ($this->viewType === 'admin') {
            $path .= '/Admin';
        }

        $file = $path . "/Views/{$this->view}.php";

        if (! file_exists($file)) {
            return;
        }

        require $file;
    }
}
