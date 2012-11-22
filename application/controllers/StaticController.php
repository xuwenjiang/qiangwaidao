<?php

class StaticController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->view->headScript()->appendFile('http://tjs.sjs.sinajs.cn/open/api/js/wb.js');
//        $this->view->headScript()->appendFile('http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2869497588');
    }

    public function indexAction()
    {
        
    }
    
    public function aboutAction()
    {
        
    }
}