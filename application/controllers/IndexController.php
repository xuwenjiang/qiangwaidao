<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $baseUrl = $this->getFrontController()->getBaseUrl();
        $baseUrl = rtrim($baseUrl, '/');
        $jsUrl = $baseUrl . '/js';
        $cssUrl = $baseUrl . '/css';
        $this->view->headScript()->appendFile($jsUrl . '/anythingslider/jquery.anythingslider.js');
        $this->view->headScript()->appendFile($jsUrl . '/anythingslider/jquery.anythingslider.video.js');
        $this->view->headLink()->appendStylesheet($cssUrl . '/anythingslider/anythingslider.css');
        $script = "// DOM Ready
            $(function(){
                $('#slider').anythingSlider({
                    resizeContents      : true,
                    addWmodeToObject    : 'transparent',
                    buildNavigation     : false,
                    buildStartStop      : false,
                    autoPlay            : true,
                    pauseOnHover        : true,
                    hashTags            : false,
                });
            });";
        $this->view->headScript()->appendScript($script);
    }
}

//<![CDATA[           
//jQuery(function(){
//    jQuery('#slider-wrap').children('ul').addClass('home-slider');
//    jQuery('.home-slider').anythingSlider({
//        hashTags:false,
//        buildArrows:false,
//        buildNavigation:false,
//        buildStartStop:true,
//        autoPlay:true,
//        pauseOnHover:true,
//        resumeOnVideoEnd:true,
//        addWmodeToObject : 'transparent',
//        delay: 10000
//    });
//});
//]]>
