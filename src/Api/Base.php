<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Api;

/**
 * Base API class
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable
 */
abstract class Base
{
    /**
     * @var string $url Base api url
     *
     * @since 1.0.0
     */
    protected $url = 'https://jsonplaceholder.typicode.com/';

    /**
     * @var string $endpoint API endpoint
     *
     * @since 1.0.0
     */
    private $endpoint = '';

    /**
     * @var string $method Request type
     *
     * @since 1.0.0
     */
    private $method = 'GET';

    /**
     * @var array $requestBody Request data
     *
     * @since 1.0.0
     */
    private $requestBody = [];

    /**
     * @var int $timeout Request timeout
     *
     * @since 1.0.0
     */
    private $timeout = 5;

    /**
     * Set current endpoint
     *
     * @param string $endpoint Endpoint for the request
     *
     * @since 1.0.0
     */
    protected function endpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Set current request method
     *
     * @param string $method Method for the request
     *
     * @since 1.0.0
     */
    protected function method(string $method)
    {
        $this->method = $method;
    }

    /**
     * Set current request body
     *
     * @param array $data Data to be used as body
     *
     * @since 1.0.0
     */
    protected function requestBody(array $data)
    {
        $this->requestBody = $data;
    }

    /**
     * Set current request timeout
     *
     * @param int $timeout Timeout for the request
     *
     * @since 1.0.0
     */
    protected function timeout(int $timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Get current request url append by endpoint
     *
     * @return string
     *
     * @since 1.0.0
     */
    private function url(): string
    {
        return $this->url . $this->endpoint;
    }

    /**
     * Get current request arguments
     *
     * @return array $argsl
     *
     * @since 1.0.0
     */
    private function args(): array
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'applcation/json',
        ];

        $args = [
            'method' => $this->method,
            'timeout' => $this->timeout,
            'headers' => $headers,
        ];

        if (! empty($this->requestBody) && $this->method === 'POST') {
            $args['body'] = $this->requestBody;
        }

        return $args;
    }

    /**
     * Do the remote request
     *
     * @param array $params Parameters to add to url
     * @return array $response
     * @throws Exception
     *
     * @since 1.0.0
     */
    protected function request(array $params = []): array
    {
        $url = ! empty($params) ? add_query_arg($params, $this->url()) : $this->url();
        $response = wp_remote_request($url, $this->args());

        if (is_wp_error($response)) {
            throw new \Exception($response->get_error_message());
        }

        return $response;
    }

    /**
     * Process the response
     *
     * @param array $response Response to be processed
     * @return array $body Processed body
     * @throws Exception
     *
     * @since 1.0.0
     */
    protected function processResponse(array $response): array
    {
        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response));

        if ($code === 200) {
            return (array) $body;
        }

        throw new \Exception(wp_remote_retrieve_response_message($response));
    }
}
