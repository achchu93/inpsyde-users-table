<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Api;

/**
 * Users API class
 *
 * @since 1.0.0
 */
class Users extends Base
{
    /**
     * Get list of users
     *
     * @return array
     *
     * @since 1.0.0
     */
    public function list(): array
    {
        $this->method('GET');
        $this->endpoint('users');

        $response = $this->request();
        return $this->processResponse($response);
    }

    /**
     * Get single users
     *
     * @param int $id Id of the user
     * @return array
     *
     * @since 1.0.0
     */
    public function item(int $id): array
    {
        $this->method('GET');
        $this->endpoint("users/{$id}");

        $response = $this->request();
        return $this->processResponse($response);
    }
}
