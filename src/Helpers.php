<?php

/**
 * Contains helper functions
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable
 */

declare(strict_types=1);

namespace Inpsyde\UsersTable\Helpers;

use Inpsyde\UsersTable\View;

/**
 * Helper function to render views
 *
 * @param string $view View file
 * @param string $type View type
 * @param array $args Arguments
 *
 * @since 1.0.0
 */
function render(string $view, string $type, array $args = [])
{
    $view = new View($view, $type);
    $view->render($args);
}

/**
 * Helper function to get view
 *
 * @param string $view View file
 * @param string $type View type
 *
 * @return string View
 *
 * @since 1.0.0
 */
function view(string $view, string $type): string
{
    $view = new View($view, $type);
    return $view->view();
}
