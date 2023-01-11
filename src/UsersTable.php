<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

/**
 * Class UsersTable
 *
 * @package Inpsyde\UsersTable
 */
final class UsersTable
{
    /**
     * Plugin version constant
     */
    private const PLUGIN_VERION = '1.0.0';

    /**
     * Plugin main file constant
     */
    private const PLUGIN_FILE = WP_PLUGIN_DIR . '/inpsyde-users-table/users-table.php';

    /**
     * Plugin main class constructor
     */
    private function __construct()
    {
        $this->init();
    }

    /**
     * Plugin main class instance
     *
     * @param string $file
     * @return UsersTable
     */
    public static function instance(): self
    {
        static $instance;

        if (! $instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Initiate plugin instances
     */
    private function init()
    {
    }

    /**
     * Plugin main file
     *
     * @return string
     */
    public function pluginFile(): string
    {
        return self::PLUGIN_FILE;
    }

    /**
     * Plugin directory url
     *
     * @return string
     */
    public function pluginDirUrl(): string
    {
        return plugin_dir_url(self::PLUGIN_FILE);
    }
}
