<?php

class Application_Form_UserLogin extends Zend_Form
{

    public function init()
    {
        $this->setName('user_login');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('用户名')
                ->setRequired(true)
                ->setDescription('请填入注册的电子邮箱地址')
                ->setAttrib('class', 'long')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('密码')
                ->setRequired(true)
                ->setDescription('请填入注册的密码')
                ->setAttrib('class', 'long')
                ->addValidator('NotEmpty');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('登录');

        $this->addElements(array($username, $password, $submit));
    }

}