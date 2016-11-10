<?php

namespace Page;

class CustomerLogin extends DefaultPage
{
    public static $URL = 'customer/account/login';

    public static $usernameField = '//*[@id="email"]';
    public static $passwordField = '//*[@id="pass"]';
    public static $loginButton = '//*[@id="send2"]';

    public function doLogin()
    {
        $name = 'roni_cost@example.com';
        $password = 'roni_cost3@example.com';
        
        $this->user->amOnPage(self::$URL);
        $this->user->fillField(self::$usernameField, $name);
        $this->user->fillField(self::$passwordField, $password);
        $this->user->click(self::$loginButton);
        $this->user->see('My Dashboard');

        return $this;
    }


}
