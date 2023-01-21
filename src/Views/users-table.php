<?php

/**
 * Users Table page main template
 *
 * @since 1.0.0
 *
 * @package Inpsyde\UsersTable
 */

declare(strict_types=1);

get_header();

// Hook to render the table content
do_action('userstable_table');

get_footer();
