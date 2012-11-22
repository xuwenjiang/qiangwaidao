<?php
class Application_View_Helper_Url extends Zend_View_Helper_Abstract
{   
    /**
     *  Get base url
     * 
     * @return string
     */
    public function baseUrl()
    {
    	return rtrim(Zend_Controller_Front::getInstance()->getBaseUrl(),'/');
    }

}