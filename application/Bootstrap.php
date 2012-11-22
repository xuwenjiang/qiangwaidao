<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
        Zend_Session::start();
        
        $this->_rewrite();
        
    }
    
    protected function _rewrite()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addRoute(
            'about-me', 
            new Zend_Controller_Router_Route_Static(
                'about-me',
                array('controller' => 'static', 'action' => 'about')
            )
        );
    }
}
