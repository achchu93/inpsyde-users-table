<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Admin;

/**
 * Class Controller
 *
 * @since 1.0.0
 *
 * @package Inpsyde\UsersTable
 */
class Controller
{
    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

        $this->init();
    }

    /**
     * Initialize classes
     *
     * @since 1.0.0
     */
    public function init()
    {
        new Settings();
    }
}
