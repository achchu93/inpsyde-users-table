<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

/**
 * Class Blocks
 *
 * @package Inpsyde\UsersTable
 */
class Blocks
{
    /**
     * @var string Plugin blocks dir constant
     *
     * @since 1.0.0
     */
    public const BLOCK_DIR = 'assets/blocks/';

    /**
     * @var string Plugin blocks view dir constant
     *
     * @since 1.0.0
     */
    public const BLOCK_VIEWS_DIR = 'src/Views/blocks/';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('init', [$this, 'registerBlocks']);
    }

    /**
     * Register all the blocks from the block directory
     *
     * @since 1.0.0
     */
    public function registerBlocks()
    {
        $blockMetaFiles = glob(UsersTable::instance()->pluginDirPath() . self::BLOCK_DIR . '*/block.json');

        foreach ($blockMetaFiles as $blockMetaFile) {
            $blockMeta = wp_json_file_decode($blockMetaFile);
            $name = $blockMeta->name;
            $name = explode('/', $name, 2)[1];

            $file = UsersTable::instance()->pluginDirPath() . self::BLOCK_VIEWS_DIR . "{$name}.php";
            $blockOptions = [];

            if (file_exists($file)) {
                $blockOptions['render_callback'] = [$this, 'renderBlock'];
            }

            register_block_type_from_metadata($blockMetaFile, $blockOptions);
        }
    }

    /**
     * Render callback for server side rendering blocks
     *
     * @param array $attributes Attributes of the block
     * @param string $content Block content save in db
     * @param WP_Block $block Block instant
     *
     * @return string Content to be rendered
     *
     * @since 1.0.0
     */
    public function renderBlock(array $attributes, string $content, \WP_Block $block): string
    {
        $name = explode('/', $block->name, 2)[1];
        $file = UsersTable::instance()->pluginDirPath() . self::BLOCK_VIEWS_DIR . "{$name}.php";

        ob_start();
        include $file;
        return ob_get_clean();
    }
}
