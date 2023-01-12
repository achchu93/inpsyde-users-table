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
 * Helper function retrieve views
 *
 * @since 1.0.0
 *
 * @param $view View file
 * @param $type View type
 * @param $args Arguments
 */
function view(string $view, string $type, array $args = [])
{
    $view = new View($view, $type);
    $view->render($args);
}
