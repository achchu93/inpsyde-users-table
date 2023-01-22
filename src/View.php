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
     *
     * @since 1.0.0
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
     *
     * @since 1.0.0
     */
    public function render(array $args = [])
    {
        $view = $this->view();

        if (!empty($view)) {
            include $view;
        }
    }

    public function view(): string
    {
        $file = $this->basePathByType() . $this->view . $this->fileExtensionByType();

        if (! file_exists($file)) {
            return '';
        }

        return $file;
    }

    /**
     * Get view paths by type
     *
     * @since 1.0.0
     */
    private function basePathByType(): string
    {
        $path = dirname(__FILE__);

        switch ($this->viewType) {
            case 'admin':
                return $path . '/Admin/Views/';
            case 'block':
                return $path . '/Views/blocks/';
            case 'html':
                return $path . '/Views/html/';
        }

        return $path . '/Views/';
    }

    /**
     * Get view extension by template type
     *
     * @return string Extension of the template
     *
     * @since 1.0.0
     */
    private function fileExtensionByType(): string
    {
        if ($this->viewType === 'html') {
            return '.html';
        }

        return '.php';
    }
}
