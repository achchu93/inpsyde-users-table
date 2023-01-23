<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

/**
 * Class Template
 *
 * @package Inpsyde\UsersTable
 */
class Template
{
    /**
     * Plugin ID constant
     *
     * @since 1.0.0
     */
    private const PLUGIN_ID = 'inpsyde-users-table/users-table';

    /**
     * Table template ID constant
     *
     * @since 1.0.0
     */
    private const TABLE_TEMPLATE_ID = 'users-table';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('init', [$this, 'rewriteRule']);
        add_filter('query_vars', [$this, 'queryVars']);
        add_filter('get_block_templates', [$this, 'addTableBlockTemplate'], 99, 3);
        add_filter('pre_get_block_template', [$this, 'addSingleTableBlockTemplate'], 99, 3);
        add_filter('get_block_file_template', [$this, 'addSingleTableBlockTemplate'], 99, 3);
        add_filter('template_include', [$this, 'loadTableTemplate']);
        add_action('userstable_table', [$this, 'renderTableTemplate']);
    }

    /**
     * Get table template full qualified ID
     *
     * @return string template ID
     *
     * @since 1.0.0
     */
    private function tableTemplateId(): string
    {
        return self::PLUGIN_ID . "//" . self::TABLE_TEMPLATE_ID;
    }

    /**
     * Add users table page rewrite rule
     *
     * @since 1.0.0
     */
    public function rewriteRule()
    {
        $settings = get_option('users_table_settings');
        $url = !empty($settings['page_url']) ? $settings['page_url'] : 'users-table';

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
     * Add table block template based on page
     *
     * @param array $template Existing templates
     * @param array $query Template query
     * @param string $type Template type
     *
     * @return array $templates Modified templates
     *
     * @since 1.0.0
     */
    public function addTableBlockTemplate(array $templates, array $query, string $type): array
    {

        if (! wp_is_block_theme() || $type !== 'wp_template') {
            return $templates;
        }

        if (is_admin()) {
            $templates[] = $this->blockTemplateInstance();
        }

        if (get_query_var('userstable')) {
            $templates = [$this->blockTemplateInstance()];
        }

        return $templates;
    }

    /**
     * Load single table block template on request
     *
     * @param WP_Block_Template $template Current template
     * @param string $id Id of the template
     * @param string $type Template type
     *
     * @return WP_Block_Template $template
     *
     * @since 1.0.0
     */
    public function addSingleTableBlockTemplate(?\WP_Block_Template $template, string $id, string $type): ?\WP_Block_Template
    {
        if ($id === $this->tableTemplateId()) {
            $template = $this->blockTemplateInstance();
        }
        return $template;
    }

    /**
     * Create block tempalte instance based on file or db
     *
     * @return WP_Block_Template
     *
     * @since 1.0.0
     */
    private function blockTemplateInstance(): \WP_Block_Template
    {
        $args = [
            'post_type' => 'wp_template',
            'posts_per_page' => -1,
            'no_found_rows' => true,
            'tax_query' => [
                [
                    'taxonomy' => 'wp_theme',
                    'field' => 'name',
                    'terms' => [ self::PLUGIN_ID, get_stylesheet() ],
                ],
            ],
            'post_name__in' => self::TABLE_TEMPLATE_ID,
        ];
        $query = new \WP_Query($args);

        $usersTableTemplate = new \WP_Block_Template();
        $usersTableTemplate = $query->have_posts() ?
            $this->savedUsersTableBlockTemplate($query->posts[0]) :
            $this->pluginUsersTableBlockTemplate();

        return $usersTableTemplate;
    }

    /**
     * Load template from db if user has edited it via editor
     *
     * @param WP_Post $postData WP Post data
     *
     * @return WP_Block_Template $template
     *
     * @since 1.0.0
     */
    private function savedUsersTableBlockTemplate(\WP_Post $postData): \WP_Block_Template
    {
        $template = new \WP_Block_Template();

        $template->wp_id = $postData->ID;
        $template->id = $this->tableTemplateId();
        $template->theme = self::PLUGIN_ID;
        $template->content = $postData->post_content;
        $template->slug = $postData->post_name;
        $template->source = 'custom';
        $template->type = $postData->post_type;
        $template->description = $postData->post_excerpt;
        $template->title = $postData->post_title;
        $template->status = $postData->post_status;
        $template->has_theme_file = true;
        $template->is_custom = false;
        $template->post_types = [];
        $template->origin = 'plugin';

        return $template;
    }

    /**
     * Load template via file
     *
     * @return WP_Block_Template
     *
     * @since 1.0.0
     */
    private function pluginUsersTableBlockTemplate(): \WP_Block_Template
    {
        $content = file_get_contents(__DIR__ . '/Views/html/users-table.html');
        $blocks = parse_blocks($content);

        // This is because we have header and footer template areas in our template
        // those template areas should be loaded via theme
        // so we inject theme name for those template areas to identify as theme template areas
        foreach ($blocks as $key => $block) {
            if ($block['blockName'] === 'core/template-part' && ! isset($block['attrs']['theme'])) {
                $blocks[$key]['attrs']['theme'] = get_stylesheet();
            }
        }

        $template = new \WP_Block_Template();
        $template->id = $this->tableTemplateId();
        $template->theme = self::PLUGIN_ID;
        $template->slug = self::TABLE_TEMPLATE_ID;
        $template->type = 'wp_template';
        $template->title = __('Users Table Page', 'users-table');
        $template->description = __('Users Table page template', 'users-table');
        $template->status = 'publish';
        $template->has_theme_file = true;
        $template->source = 'plugin';
        $template->origin = 'plugin';
        $template->is_custom = false;
        $template->post_types = [];
        $template->area = 'uncategorized';
        $template->content = serialize_blocks($blocks);

        return $template;
    }

    /**
     * Load non block theme template
     *
     * @param string $template Current template
     *
     * @return string $template New template
     *
     * @since 1.0.0
     */
    public function loadTableTemplate(string $template): string
    {
        if (get_query_var('userstable') && !wp_is_block_theme()) {
            $template = UsersTable::instance()->pluginDirPath() . 'src/Views/users-table.php';
        }
        return $template;
    }

    /**
     * Renders the main table part
     *
     * @since 1.0.0
     */
    public function renderTableTemplate()
    {
        Helpers\render('users-table', 'block');
    }
}
