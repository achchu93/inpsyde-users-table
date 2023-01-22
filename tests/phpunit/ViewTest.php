<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Tests\PhpUnit;

use Inpsyde\UsersTable\View;

/**
 * View Test class
 *
 * @since 1.0.0
 * @package Inpsyde\UsersTable\Tests\PhpUnit
 */
class ViewTest extends BaseTestCase
{
    /**
     * Tests if the render contains expected string
     *
     * @since 1.0.0
     */
    public function testRender()
    {
        $view = new View('users-table', 'html');

        ob_start();
        $view->render();
        $viewContent = ob_get_clean();
        $content = '<!-- wp:inpsyde/users-table /-->';

        self::assertStringContainsString($content, $viewContent);
    }

    /**
     * Tests if the render is empty when invalid file given
     *
     * @since 1.0.0
     */
    public function testRenderFailure()
    {
        $view = new View('invalid-file', 'invalid-extension');

        ob_start();
        $view->render();
        $viewContent = ob_get_clean();

        self::assertEmpty($viewContent);
    }
}
