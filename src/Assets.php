<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

/**
 * Class Blocks
 *
 * @package Inpsyde\UsersTable
 */
class Assets
{
    /**
     * @var string Assets build directory
     */
    private const ASSET_BUILD_DIR = 'build';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueTableAssets']);
    }

    /**
     * Enqueue Table page styles and scripts
     *
     * @since 1.0.0
     */
    public function enqueueTableAssets()
    {
        if (!get_query_var('userstable')) {
            return;
        }

        $assetFile = include UsersTable::instance()->pluginDirPath() . 'build/frontend/index.asset.php';

        wp_enqueue_script(
            'users-table-frontend',
            $this->assetUrl('frontend/index.js'),
            $assetFile['dependencies'],
            $assetFile['version'],
            true
        );

        wp_localize_script(
            'users-table-frontend',
            'UsersTable',
            [
                'nonce' => wp_create_nonce('userstable-nonce'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'tableColumns' => [
                    [
                        'id' => 'id',
                        'name' =>  __('ID', 'users-table'),
                        'sortable' => true,
                    ],
                    [
                        'id' => 'name',
                        'name' => __('Name', 'users-table'),
                        'sortable' => true,
                    ],
                    [

                        'id' => 'username',
                        'name' => __('Username', 'users-table'),
                        'sortable' => true,
                    ],
                ],
            ]
        );
    }

    /**
     * Get asset url for the file
     *
     * @return string $file File url
     *
     * @since 1.0.0
     */
    private function assetUrl($file): string
    {
        return UsersTable::instance()->pluginDirUrl() . self::ASSET_BUILD_DIR . "/{$file}";
    }
}
