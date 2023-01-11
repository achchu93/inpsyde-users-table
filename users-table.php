<?php

/**
 * Plugin Name:       Inpsyde Users Table
 * Plugin URI:        https://github.com/achchu93/inpsyde-users-table
 * Description:       A custom Users table implementaion in a custom page.
 * Version:           1.0.0
 * Author:            Ahamed Arshad
 * Author URI:        https://github.com/achchu93
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       users-table
 * Domain Path:       /languages
 *
 * @link              https://github.com/achchu93/inpsyde-users-table
 * @since             1.0.0
 * @package           Inpsyde\UsersTable
 */

declare(strict_types=1);

namespace Inpsyde\UsersTable;

// reuire composer autoload file
require_once __DIR__ . '/vendor/autoload.php';

// initiate the plugin
UsersTable::instance();
