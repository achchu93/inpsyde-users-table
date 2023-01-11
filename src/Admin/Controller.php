<?php

declare(strict_types=1);

namespace Inpsyde\UsersTable\Admin;

class Controller
{
    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        new Settings();
    }
}
