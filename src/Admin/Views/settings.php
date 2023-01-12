<?php

/**
 * Template for users table settings page
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable
 */

declare(strict_types=1);

?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('users_table');

        do_settings_sections('users_table');

        submit_button(__('Save'));
        ?>
    </form>
</div>
