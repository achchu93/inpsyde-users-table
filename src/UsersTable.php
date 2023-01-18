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
        // add_filter('template_include', [$this, 'template']);
        add_filter('get_block_templates', [$this, 'addTableBlockTemplate'], 99, 3);
        add_filter('pre_get_block_template', [$this, 'addSingleTableBlockTemplate'], 99, 3);
        // add_filter( 'pre_get_block_file_template', function($null, $id, $template_type){
        //     var_dump([$id, $template_type]);
        //     return $null;
        // });
        add_filter('page_template_hierarchy', function($templates){
            // var_dump($templates);
            if (get_query_var('userstable')) {
                $templates = ['users-table.html'];
            }
            var_dump($templates);
            return $templates;
        } );
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
    public function template(string $template): string
    {
        if (filter_var(get_query_var('userstable'), FILTER_VALIDATE_BOOLEAN)) {
            $template = __DIR__ . '/Views/users-table.php';
            // $template = locate_block_template(
            //     dirname(__FILE__) . '/Views/users-table.php',
            //     'users-table',
            //     []
            // );
        }

        return $template;
    }

    public function addTableBlockTemplate(array $templates, array $query, string $type): array
    {

        if (! wp_is_block_theme() || $type !== 'wp_template') {
            return $templates;
        }

        // var_dump(count($templates));

        if (!get_query_var('userstable')) {
            return $templates;
        }

        $templates[] = $this->blockTemplateInstance();

        return $templates;
    }

    public function addSingleTableBlockTemplate(?\WP_Block_Template $template, string $id, string $type): ?\WP_Block_Template
    {
        if ($id === 'inpsyde/userstable//users-table') {
            $template = $this->blockTemplateInstance();
        }
        return $template;
    }

    private function blockTemplateInstance(): \WP_Block_Template
    {
        $content = file_get_contents(__DIR__ . '/Views/html/users-table.html');
        $blocks = parse_blocks($content);

        foreach ($blocks as $key => $block) {
            if ($block['blockName'] === 'core/template-part' && ! isset($block['attrs']['theme'])) {
                $blocks[$key]['attrs']['theme'] = get_stylesheet();
            }
        }

        $usersTableTemplate = new \WP_Block_Template();
        $usersTableTemplate->id = 'inpsyde/userstable//users-table';
        $usersTableTemplate->theme = 'inpsyde-users-table/users-table';
        $usersTableTemplate->slug = 'users-table';
        $usersTableTemplate->type = 'wp_template';
        $usersTableTemplate->title = __('Users Table Page', 'users-table');
        $usersTableTemplate->description = __('Users Table page template', 'users-table');
        $usersTableTemplate->status = 'publish';
        $usersTableTemplate->has_theme_file = true;
        $usersTableTemplate->source = 'plugin';
        $usersTableTemplate->origin = 'plugin';
        $usersTableTemplate->is_custom = false;
        $usersTableTemplate->post_types = [];
        $usersTableTemplate->area = 'uncategorized';
        $usersTableTemplate->content = serialize_blocks($blocks);

        return $usersTableTemplate;
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
