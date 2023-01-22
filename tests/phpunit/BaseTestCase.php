<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Tests\PhpUnit;

use PHPUnit\Framework\TestCase;
use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Base tests class to use by Tests
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable\Tests\PhpUnit
 */
abstract class BaseTestCase extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * pre setup method
     *
     * @since 1.0.0
     */
    protected function setUp(): void
    {
        parent::setup();
        Monkey\setup();
    }

    /**
     * post down method
     *
     * @since 1.0.0
     */
    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
