<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Admin;

use Inpsyde\UsersTable\Helpers;

/**
 * Settings class
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable
 */
class Settings
{
    /**
     * Settings constructor
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'pluginSettings']);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('update_option_users_table_settings', 'flush_rewrite_rules');
    }

    /**
     * Add settings page to menu
     *
     * @since 1.0.0
     */
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

    /**
     * Register settings sections and fields
     *
     * @since 1.0.0
     */
    public function registerSettings()
    {
        register_setting(
            'users_table',
            'users_table_settings',
            [
                'sanitize_callback' => [$this, 'sanitizeSettings'],
            ]
        );

        add_settings_section(
            'page_url_settings',
            __('Page url'),
            [$this, 'settingsUrlSection'],
            'users_table'
        );

        add_settings_field(
            'page_url',
            __('URL'),
            [$this, 'urlField'],
            'users_table',
            'page_url_settings',
            [
                'label_for' => 'page_url',
            ]
        );

        // This is to flush the rules if it hasn't cleared it already
        if (delete_transient('users_table_flush_rules')) {
            flush_rewrite_rules();
        }
    }

    /**
     * Sanitize settings data
     *
     * @param array $data Posted data array
     * @return array $data Sanitized data array
     */
    public function sanitizeSettings(array $data): array
    {
        if (! is_array($data)) {
            $data = [];
        }

        // validate endpoint to have single string
        if (strpos($data['page_url'], '/') !== false) {
            $data['page_url'] = explode('/', $data['page_url'])[0];
        }

        // To determine if rewrite rules are not generated yet
        set_transient('users_table_flush_rules', true);

        return $data;
    }

    /**
     * Render settings main page
     *
     * @since 1.0.0
     */
    public function renderSettingsPage()
    {
        Helpers\render('settings', 'admin');
    }

    /**
     * Render settings url section
     *
     * @since 1.0.0
     */
    public function settingsUrlSection()
    {
        Helpers\render('settings-section-url', 'admin');
    }

    /**
     * Render settings url field
     *
     * @since 1.0.0
     *
     * @param array $args Arguments
     */
    public function urlField(array $args)
    {
        Helpers\render('settings-field-url', 'admin', $args);
    }
}
