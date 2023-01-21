<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

use Inpsyde\UsersTable\Admin\Settings;

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
        new Template();
        new Blocks();
        new Assets();
    }

    /**
     * Init plugin hooks
     *
     * @since 1.0.0
     */
    private function initHooks()
    {
        register_activation_hook(self::PLUGIN_FILE, [$this, 'activate']);
        register_deactivation_hook(self::PLUGIN_FILE, [$this, 'deactivate']);
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

    /**
     * Get plugin dir path
     *
     * @return string Directory path of plugin
     *
     * @since 1.0.0
     */
    public function pluginDirPath(): string
    {
        return plugin_dir_path(self::PLUGIN_FILE);
    }

    /**
     * Plugin activation hook
     *
     * @since 1.0.0
     */
    public function activate()
    {
        set_transient('users_table_flush_rules', true);

        $activate = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        $checked = isset($_POST['checked']) && is_array($_POST['checked']) ? $_POST['checked'] : []; // phpcs:ignore

        if ('activate-selected' === $activate && count($checked) > 1) {
            return; // bail out if plugin bulk activation
        }

        set_transient(Settings::REDIRECT_TRANSIENT_KEY, 5 * MINUTE_IN_SECONDS);
    }

    /**
     * Plugin deactivation hook
     *
     * @since 1.0.0
     */
    public function deactivate()
    {
        global $wp_rewrite;

        $settings = get_option('users_table_settings');
        $url = !empty($settings['page_url']) ? $settings['page_url'] : 'users-table';

        // For some reason just the flush rewrite rules not working.
        // So we unset the rule here and flush the rewrite rules
        unset($wp_rewrite->extra_rules_top["{$url}/?$"]);
        flush_rewrite_rules(true);
    }
}
