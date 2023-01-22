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

## Technical Documentation

#### Activation and Deactivation
When activating/deactivating the plugin, some actions are applied by the plugin. Which are,

- **Redirecting on activation** - As the plugin allows users to customize the users table page url, It's been redirecting users to plugins settings page to set url. Where users can set the url as per their wish and from there they can navigate to the url which they have set.

- **Flush Rewrite Rules on activation/deactivation** - Since plugin has a custom url for the table page, It uses activation and deactivation hook to flush the rewrite rule. That way new url would get activated when activating the plugin and 404 url would get activated once the plugin got activated.

- **Transient API Usage** - First we set the rewrite rule flush transient to detect if rewrite rule is need to flushed. This is because flushing the cache on activating the plugin is not gonna work as our custom rewrite rule get added on `init` hook. Again when the init hooks is triggered we check if transient is set and then flush rules and delete the transient.

  Another instance is, To redirect the user to settings page, plugin should know if the plugin's state is changed and need to be redirected on actviation. Therefore transient cache is added while activating and on the next `admin_init` hook it will be redirected to settings page.


#### Views Controller

Plugin contains a class to control the views within the plugin. Instead of doing `require` all over the plugin, The controller does everything based on the template type. It can handle block template views, Admin views, and Other general views as well. The controller is consist of 2 main actions which are, render the template and return the template.

#### Blocks

All the blocks are from `assets/blocks` directory will be registered as blocks. Block will be determined it's type based on if it has a template file with the same name in template directory. Check `users-table` block how it get added.

#### API

The external API is managed by a `Base` class where basic properties and methods are available. Currently for the `/user` endpoint we have extended a class with the `Base`. For any single endpoint extending the `Base` class would do the trick.

We use WP cache mechanism to store users data which is retrieved from external API. By caching it, we can gain a performance improvement and we can avoid unnecessary API calls within a time frame. For now response will be save for an hour.
