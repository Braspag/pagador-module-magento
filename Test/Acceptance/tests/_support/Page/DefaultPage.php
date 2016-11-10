<?php

namespace Page;

abstract class DefaultPage
{
    public $user;

    public function __construct(\AcceptanceTester $user)
    {
        $this->user = $user;
    }

    public static function of(\AcceptanceTester $user)
    {
        return new static($user);
    }
}
