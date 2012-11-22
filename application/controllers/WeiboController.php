<?php
class WeiboController extends Zend_Controller_Action
{
    public function preDispatch(){
        $this->getResponse()->setHeader('Content-type', 'text/plain')
                            ->setHeader('Cache-Control','no-cache');
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
    }

    
    public function connectAction()
    {
        $i=0;
    }

}