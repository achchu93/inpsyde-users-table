# Inpsyde Users Table

A WordPress plugin to show list of users using [JSON Placeholder Api](https://jsonplaceholder.typicode.com/).

## Features
- Allow users to set own endpoint to the table page
- Customizable template for block based themes via block editor
- A table block to use it anywhere with the Gutenberg support

## Requirements
A WordPress environment with,
- WordPress version >= 6.0
- PHP versions >= 7.2 or more
- Composer
- NPM

## Installation Guide
- Clone/Download the repo to WordPress plugins folder eg: `/wp-content/plugins`
- Run `composer install` from plugin root directory eg: `wp-content/plugins/inpsyde-users-table`
- Run `npm install` and `npm run build:all` respectively
- Activate the plugin through WP Admin interface


## Block theme compatibility
Plugin comes with a template which can be editable via WP Block Template Editor. The deafault template is with theme header and footer with Users Table block included in the content area. Users can add as many as block before or after the users table via Gutenberg editor.

**Important:** Users Table block should not be removed from template.


## Classic theme compatibility
As the Gutenberg editor and Block themes get more advanced day by day, This plugin also gives more flexibility to block themes over classic themes. Yet, you are not alone while using classic themes. Plugin uses some hooks and you can customize the template via hooks.

### Hooks

`userstable_table` - Plugin uses default priority, which is 10 to render the table. therefore adding content before the table should be hooked with early priority and content after table should be hooked higher priority of 10.

```
add_action('userstable_table', 'my_users_table_before_title', 9);

function my_users_table_before_title()
{
    echo '<h2>' . esc_html('My top rated customers') . '</h2>'
}
```
