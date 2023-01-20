<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable;

use Inpsyde\UsersTable\Api\Users;

/**
 * Ajax class
 *
 * @since 1.0.0
 */
class Ajax
{
    /**
     * @var array $actions
     *
     * @since 1.0.0
     */
    private $actions;

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        if (! wp_doing_ajax()) {
            return;
        }

        $this->actions = [
            'ut_get_users' => [
                true,
                [ $this, 'usersList' ],
            ],
        ];

        foreach ($this->actions as $action => $data) {
            add_action('wp_ajax_' . $action, $data[1]);

            if ($data[0]) {
                add_action('wp_ajax_nopriv_' . $action, $data[1]);
            }
        }
    }

    /**
     * Users list ajax method
     *
     * @since 1.0.0
     */
    public function usersList()
    {
        check_ajax_referer('userstable-nonce');

        $users = get_transient('ut-users-list');
        if (is_array($users)) {
            wp_send_json_success($users);
        }

        try {
            $users = new Users();
            $list = $users->list();

            set_transient('ut-users-list', $list, HOUR_IN_SECONDS);

            wp_send_json_success($list);
        } catch (\Exception $exp) {
            wp_send_json_error($exp->getMessage());
        }
    }
}
