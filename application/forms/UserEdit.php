<?php

class Application_Form_UserEdit extends Zend_Form
{

    public function init()
    {
        $this->setName('user_edit');
        
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('昵称')
//                ->setAttrib('disabled', true)
                ->setAttrib('class', 'long');

        $gender = new Zend_Form_Element_Select('gender');
        $gender->setLabel('我是')
                ->setMultiOptions(array(
                    '' => '请选择',
                    'female' => '小花',
                    'male' => '小草'))
                ->setRequired(true)
                ->setDescription('选择性别');

        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('电子邮箱')
                ->setDescription('请输入邮箱以方便大家联系你')
                ->setAttrib('class', 'long');

        $image = new Zend_Form_Element_File('file');
        $image->addValidator('Extension', false, 'jpg,jpeg,png,gif')
                ->setMaxFileSize(1024*1024)
                ->setAttrib('accept', "image/jpeg,image/gif,image/png")
                ->setLabel('头像')
                ->addValidator('Size', false, 1024*1024)
                ->setDescription('请选择小于1MB的图片作为个性头像');;

        $update = new Zend_Form_Element_Submit('update');
        $update->setLabel('更新');

        $this->addElements(array($name, $gender, $email, $image, $update));
    }

}