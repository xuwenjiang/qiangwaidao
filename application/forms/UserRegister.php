<?php

class Application_Form_UserRegister extends Zend_Form
{

    public function init()
    {
        $this->setName('user_register');

        $username = new Zend_Form_Element_Text('username');
        $username->setLabel('用户名')
                ->setRequired(true)
                ->setAttrib('class', 'long')
                ->setDescription('登录用，请填入电子邮箱地址')
                ->addValidator('NotEmpty')
                ->addValidator('EmailAddress');

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('密码')
                ->setRequired(true)
                ->setAttrib('class', 'long')
                ->addValidator('NotEmpty');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('昵称')
                ->setAttrib('class', 'long')
                ->setRequired(true)
                ->setDescription('给自己起个响亮的名号吧')
                ->addValidator('NotEmpty');

        $gender = new Zend_Form_Element_Select('gender');
        $gender->setLabel('我是')
                ->setMultiOptions(array(
                    '' =>'请选择',
                    'female' => '小花',
                    'male' => '小草'))
                ->setRequired(true)
                ->setDescription('选择性别');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('注册');

        $this->addElements(array($username, $password, $name,$gender, $submit));
    }

}