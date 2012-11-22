<?php

class Application_Model_Session_User extends Application_Model_Session_Abstract
{

    function __construct($namespace = 'User', $singleInstance = false)
    {
        parent::__construct($namespace, $singleInstance);
    }

    public function getUser()
    {
        if ($this->isUserLoggedIn())
        {
            return $this->user;
        }
        return null;
    }

    public function logIn($userId=null)
    {
        if ($userId)
        {
            $user = new Application_Model_DbTable_User();
            $this->user = $user->getUser($userId);
            $this->userLoggedId = true;
        }
        return $this;
    }

    public function logOff()
    {
        unset($this->user);
        $this->userId = null;
        $this->userLoggedId = false;
        return $this;
    }

    public function isUserLoggedIn()
    {
        return $this->userLoggedId;
    }

}
