<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Admin;

class Settings
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'pluginSettings']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function pluginSettings()
    {
        add_options_page(
            __('Users Table Settings'),
            __('Users Table'),
            'manage_options',
            'users-table-settings',
            [$this, 'renderSettingsPage']
        );
    }

    public function registerSettings()
    {
        register_setting('users_table', 'users_table_settings');

        add_settings_section(
            'page_url_settings',
            __('Page url'),
            [$this, 'page_url_section'],
            'users_table'
        );
    }
}
