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
            'ut_get_user' => [
                true,
                [ $this, 'singleUser' ],
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

    /**
     * Single user ajax method
     *
     * @since 1.0.0
     */
    public function singleUser()
    {
        check_ajax_referer('userstable-nonce');

        $id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);

        if (! $id) {
            wp_send_json_error(__('Invalid User Id', 'users-table'));
        }

        $user = get_transient("ut-user-{$id}");
        if ($user !== false) {
            wp_send_json_success($user);
        }

        try {
            $user = new Users();
            $user = $user->item($id);

            set_transient("ut-user-{$id}", $user, HOUR_IN_SECONDS);

            wp_send_json_success($user);
        } catch (\Exception $exp) {
            wp_send_json_error($exp->getMessage());
        }
    }
}
