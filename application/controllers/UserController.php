<?php

class UserController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $userSession = new Application_Model_Session_User();
        $this->view->userSession = $userSession;
    }

    public function loginAction()
    {
        if ($this->_isUserLogin()) {
            $this->_redirect('user/profile');
        }

        $userLoginForm = new Application_Form_UserLogin();
        $this->view->userLoginForm = $userLoginForm;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($userLoginForm->isValid($formData)) {
                $username = $userLoginForm->getValue('username');
                $password = $userLoginForm->getValue('password');

                $user = new Application_Model_DbTable_User();

                $row = $user->getUserByField('username', $username);
                if ($row && $row->id) {
                    if ($row->password = md5($password)) {
                        $userSession = new Application_Model_Session_User();
                        $userSession->logIn($row->id);
                        $this->_redirect('index');
                    }
                } else {
                    $this->view->errorMessage = '登录失败，但我不告诉你为啥';
                }
            }
        }
    }

    public function logoffAction()
    {
        $userSession = new Application_Model_Session_User();
        $userSession->logOff();
        $this->_redirect($this->getFrontController()->getBaseUrl());
    }

    public function registerAction()
    {
        if ($this->_isUserLogin()) {
            $this->_redirect('user/profile');
        }

        $userRegisterForm = new Application_Form_UserRegister();
        $this->view->userRegisterForm = $userRegisterForm;
        $this->view->errorMessage = null;

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($userRegisterForm->isValid($formData)) {
                $username = $userRegisterForm->getValue('username');
                $password = $userRegisterForm->getValue('password');
                $name = $userRegisterForm->getValue('name');
                $data = $userRegisterForm->getValues();

                $user = new Application_Model_DbTable_User();

                $row = $user->getUserByField('username', $username);
                if ($row && $row->id) {
                    $userRegisterForm->populate($formData);
                    $this->view->errorMessage = '用户名已被使用，请重新注册';
                    return;
                }

                $row = $user->getUserByField('name', $name);
                if ($row && $row->id) {
                    $userRegisterForm->populate($formData);
                    $this->view->errorMessage = '昵称已被使用，请重新注册';
                    return;
                }

                $data['password'] = md5($password);
                $data['created_at'] = date('Y-m-d H:i:s');

                $userId = $user->addUser($data);
                if ($userId) {
                    $userSession = new Application_Model_Session_User();
                    $userSession->logIn($userId);
                    $this->_redirect('index');
                } else {
                    $userRegisterForm->populate($formData);
                    $this->view->errorMessage = '注册失败，暂时闹不清咋回事儿';
                    return;
                }
            }
        }
    }

    public function profileAction()
    {
        try{
            
    
        $baseUrl = $this->getFrontController()->getBaseUrl();
        $baseUrl = rtrim($baseUrl, '/');
        $jsUrl = $baseUrl . '/js';
        $cssUrl = $baseUrl . '/css';
        $this->view->headScript()->appendFile($jsUrl . '/jcrop/jquery.Jcrop.js');
        $this->view->headLink()->appendStylesheet($cssUrl . '/jcrop/jquery.Jcrop.css');
        $script = "
            $(function()
            {
                $('#cropbox').Jcrop({
                    aspectRatio: 1,
                    onSelect: updateCoords
                });
            });
            
            function updateCoords(c)
            {
                $('#x').val(c.x);
                $('#y').val(c.y);
                $('#w').val(c.w);
                $('#h').val(c.h);
            };
            
            function checkCoords()
            {
                if (parseInt($('#w').val())) return true;
                alert('Please select a crop region then press submit.');
                return false;
            };";

        $this->view->headScript()->appendScript($script);

        if (!$this->_isUserLogin()) {
            $this->_redirect('user/login');
        }

        $userEditForm = new Application_Form_UserEdit();

        $this->view->userEditForm = $userEditForm;

        $userSession = new Application_Model_Session_User();

        $user = $userSession->getUser();

        $messages = array();

        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getParam('crop')) {
                $this->_cropAvatar($this->getRequest()->getParam('ratio'));
            } else {
                if (key_exists('file', $_FILES)) {
                    $messages[] = $this->_uploadImage($user);
                }

                $email = $this->getRequest()->getParam('email');
                if ($email) {
                    $user->email = $email;
                }
                
                $name = $this->getRequest()->getParam('name');
                if ($name) {
                    $user->name = $name;
                }

                $userDB = new Application_Model_DbTable_User();
                $userDB->updateUser($user->id, $user->toArray());
            }
            $this->_redirect('user/profile');
        }
        
        $this->view->user = $user;
        
        $userEditForm->populate($user->toArray());
        }  catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }

    protected function _cropAvatar($ratio)
    {
        $userSession = new Application_Model_Session_User();
        $user = $userSession->getUser();

        if (!$this->_isUserLogin()) {
            $this->_redirect('user/login');
        }

        $paths = $this->getInvokeArg('bootstrap')->getOption('path');

        $appPath = $paths['app'];

        $avatarFile = $appPath . $user->image;

        if (!file_exists($avatarFile)) {
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $targ_w = $targ_h = 180;
            $quality = 95;
            
            $extention = strtolower(end(explode('.', $user->image)));

            $src = $avatarFile;
            switch ($extention) {
                case 'jpeg':
                case 'jpg':
                    $img_r = imagecreatefromjpeg($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x']*$ratio, $_POST['y']*$ratio, $targ_w, $targ_h, $_POST['w']*$ratio, $_POST['h']*$ratio);
                    imagejpeg($dst_r, $src, $quality);
                    break;
                
                case 'gif':
                    $img_r = imagecreatefromgif($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x']*$ratio, $_POST['y']*$ratio, $targ_w, $targ_h, $_POST['w']*$ratio, $_POST['h']*$ratio);
                    imagegif($dst_r, $src, $quality);
                    break;
                case 'png':
                    $img_r = imagecreatefrompng($src);
                    $dst_r = ImageCreateTrueColor($targ_w, $targ_h);
                    imagecopyresampled($dst_r, $img_r, 0, 0, $_POST['x']*$ratio, $_POST['y']*$ratio, $targ_w, $targ_h, $_POST['w']*$ratio, $_POST['h']*$ratio);
                    imagepng($dst_r, $src, $quality);
                    break;
                default:
                    break;
            }
        }
    }

    protected function _uploadImage($user)
    {
        if ($_FILES['file']['error'] != UPLOAD_ERR_OK) {
            return '头像上传失败';
        }

        $paths = $this->getInvokeArg('bootstrap')->getOption('path');

        $imagePath = $paths['image'];

        if (!file_exists($imagePath . DIRECTORY_SEPARATOR . 'user')) {
            mkdir($imagePath . DIRECTORY_SEPARATOR . 'user', 0777);
        }

        $avatarDir = $imagePath . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR . $user->id;

        if (!file_exists($avatarDir)) {
            mkdir($avatarDir, 0777);
        }

        $extention = end(explode('.', $_FILES['file']['name']));
        $avatar = 'avatar' . '.' . $extention;

        $tmp_name = $_FILES['file']["tmp_name"];
        $avatarName = $avatarDir . DIRECTORY_SEPARATOR . $avatar;

        if (move_uploaded_file($tmp_name, $avatarName)) {
            $user->image = '/img/user/' . $user->id . DIRECTORY_SEPARATOR . $avatar;
            return '头像上传成功';
        } else {
            return '头像上传失败';
        }
    }

    protected function _isUserLogin()
    {
        $userSession = new Application_Model_Session_User();
        return $userSession->isUserLoggedIn();
    }

}

