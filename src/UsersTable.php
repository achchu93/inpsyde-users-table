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
        $this->initHooks();
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
        new Admin\Controller();
        new Ajax();
    }

    /**
     * Init plugin hooks
     *
     * @since 1.0.0
     */
    private function initHooks()
    {
        add_action('init', [$this, 'rewriteRule']);
        add_filter('query_vars', [$this, 'queryVars']);
        add_action('template_redirect', [$this, 'template']);
    }

    /**
     * Add users table page rewrite rule
     *
     * @since 1.0.0
     */
    public function rewriteRule()
    {
        $settings = get_option('users_table_settings');
        $url = isset($settings['page_url']) ? $settings['page_url'] : 'users-table';

        add_rewrite_rule("{$url}/?$", 'index.php?userstable=true', 'top');
    }

    /**
     * Add users table custom query var
     *
     * @since 1.0.0
     *
     * @param array $queryVars Default query vars
     * @return array $queryVards Modified query vars
     */
    public function queryVars(array $queryVars): array
    {
        $queryVars[] = 'userstable';
        return $queryVars;
    }

    /**
     * Set template for users table page
     *
     * @since 1.0.0
     */
    public function template()
    {
        if (! filter_var(get_query_var('userstable'), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        Helpers\view('users-table', 'general');
        exit;
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
