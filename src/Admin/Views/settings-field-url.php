<?php

/**
 * Template for users table settings url field
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable
 */

declare(strict_types=1);

$settings = get_option('users_table_settings');
$url = isset($settings['page_url']) ? $settings['page_url'] : '';

//phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$name = $args['label_for'];
?>

<input
    id="<?php echo esc_attr($name); ?>"
    name="users_table_settings[<?php echo esc_attr($name); ?>]"
    type="text" placeholder="users-table"
    value="<?php echo esc_attr($url); ?>"
/>
<p class="description">
    <small>
    <?php
    echo sprintf(
        // translators: %s: users table page url
        esc_html__(
            'Endpoint without any slashes. Eg: users-table. So it would become %s',
            'users-table'
        ),
        esc_url(get_site_url(null, 'users-table'))
    );
    ?>
    </small>
</p>
