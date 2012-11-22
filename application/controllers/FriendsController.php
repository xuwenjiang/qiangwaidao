<?php

class FriendsController extends Zend_Controller_Action
{

    public function init()
    {
        $contextSwitch = $this->_helper->getHelper('contextSwitch');
        $contextSwitch->addActionContext('list', 'xml')
                ->initContext();
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $userSession = new Application_Model_Session_User();
        $this->view->userSession = $userSession;
    }

    public function mapAction()
    {
        $userSession = new Application_Model_Session_User();
        $this->view->userSession = $userSession;

        $user = $userSession->getUser();
        $this->view->user = $user;
        
        $userDB = $userDB = new Application_Model_DbTable_User();
        $otherUsers = $userDB->getAllUsers("last_latitude is not null and id != $user->id");
        $this->view->otherUsers = $otherUsers;

        $this->_loadMapHeadJavascript();
    }

    public function saveLocationAction()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {

            $latitude = $this->getRequest()->getParam('latitude');
            $longitude = $this->getRequest()->getParam('longitude');
            
            $userSession = new Application_Model_Session_User();
            $user = $userSession->getUser();
            
            $user->last_latitude = $latitude;
            $user->last_longitude = $longitude;
            
            $userDB = new Application_Model_DbTable_User();
            $userDB->updateUser($user->id, $user->toArray());
            
            $this->_helper->layout->disableLayout();
            $this->_helper->viewRenderer->setNoRender(TRUE);
            echo '您的地理信息已更新';
        } else {
            
        }
    }

    protected function _loadMapHeadJavascript()
    {
        $baseUrl = $this->getFrontController()->getBaseUrl();
        $baseUrl = rtrim($baseUrl, '/');
        $jsUrl = $baseUrl . '/js';
        $cssUrl = $baseUrl . '/css';

        $this->view->headScript()->appendFile('http://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyBD_6mKN9SvEXBYSeE8AYzRB5TLBlozsjs&sensor=true');
    }

}