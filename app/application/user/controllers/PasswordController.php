<?php
class User_PasswordController extends Zend_Controller_Action
{
	public function indexAction()
    {
        $this->view->headTitle("修改密码");
        $csu = Class_Session_User::getInstance();
        require_once APP_PATH."/user/forms/Password/Edit.php";
        $form = new Form_Password_Edit();
        
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $formArr = $form->getValues();
            $userId = $csu->getUserId();
            $userDoc = App_Factory::_m('User')->find($userId);
            if(Class_Session_User::getMd5Password($formArr['password_old']) != $userDoc->password) {
                $form->setDescription('原密码不正确，请输入正确的原密码！');
            } else {
            	$userDoc->password = Class_Session_User::getMd5Password($formArr['password']);;
            	$userDoc->save();
            	$this->_helper->flashMessenger->addMessage('密码修改已成功！');
            }
        }
        $this->view->form = $form;
        $this->view->loginName = $csu->getLoginName();
    }
    
	public function forgotPasswordAction()
    {
        $this->view->headTitle("忘记密码 ");
        if ($this->getRequest()->isPost()) {
            $email = $this->getRequest()->getParam('email');
            $f = new Zend_Filter_StripTags();
            $email = $f->filter($email);
            $v1 = new Zend_Validate_EmailAddress();
            $v2 = new Zend_Validate_NotEmpty();
            if ($v2->isValid($email)) {
                if ($v1->isValid($email)) {
                    //取出密码:
                    $pwd_old = Model_User::getPassword($email);
                    if (empty($pwd_old)) {
                        $result = '当前email不是注册邮箱,请输入注册的邮箱';
                        //echo '当前email不是注册邮箱,请输入注册的邮箱';
                    } else {
                        //$pwd_old = md5($pwd_old.MD5_SALT);
                        //现在就向用户发送一封邮件,把密码发送给用户
                        require_once 'Zend/Mail.php';
                        require_once 'Zend/Mail/Transport/Smtp.php';
                        $config = array('auth' => 'login' , 'username' => 'support@pmatch.cn' , 'password' => 'beautifulworld9924');
                        $transport = new Zend_Mail_Transport_Smtp('smtp.pmatch.cn', $config);
                        $mail = new Zend_Mail();
                        $mail->addHeader('X-MailGenerator', 'MyCoolApplication');
                        $unix = mktime();
                        //$unix = Model_User::encrypt("123456", $unix);
                        $unix = base64_encode($unix);
                        $html = "请点击下面重设密码链接重设您的密码有效期为24小时<br />
                     <a href=\"http://".$_SERVER ['HTTP_HOST']."/user/reset-password/email/$email/unix/$unix/pwd/$pwd_old\">重设您的密码</a><br /><br />
                     如不能点击请在您的浏览器中复制如下链接<br />
                     http://".$_SERVER ['HTTP_HOST']."/user/reset-password/email/$email/unix/$unix/pwd/$pwd_old";
                        $mail->setBodyHtml($html, 'utf-8', Zend_Mime::ENCODING_BASE64);
                        // $mail->setBodyHtml($text);
                        $mail->setFrom('support@pmatch.cn', "=?UTF-8?B?" . base64_encode('眠趣网') . "?=");
                        $mail->addTo($email, $email);
                        $subject = '重设密码';
                        $subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
                        $mail->setSubject($subject);
                        if ($mail->send($transport)) {
                            $result = '邮箱已发送,请查收后修改密码';
                            //echo '邮箱已发送,请查收后修改密码';
                        } else {

                        }
                    }
                } else {
                    $result = 'email格式不对,请重新输入';
                    //echo 'email格式不对,请重新输入';
                }
            } else {
                $result = 'email不能为空';
                //echo 'email不能为空';
            }
            $this->view->result = $result;
        }
    }
	
	public function resetAction()
    {
        $this->view->headTitle("重设密码");
        $email = base64_decode($this->getRequest()->getParam('email'));
        $pwd = $this->getRequest()->getParam('code');
        
        $form = null;
        if(!empty($email) && !empty($pwd)) {
            $customer = Class_Core::_('Customer')->setData('email', $email)
                ->load();
            $origPassword = $customer->getData('password');
            echo $email.'<br />';
            echo $origPassword.'<br />';
            echo $customer->getData('entityId').'<br />';
            if (md5($origPassword.$customer->getData('entityId')) == $pwd) {
                require APP_PATH.'/default/forms/resetPassword.php';
                $form = new Form_ResetPassword();
                if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
                    $customer->setData('password', md5($form->getValue('password').MD5_SALT))
                        ->save();
                    
                    Class_Customer::login($customer);
                    Class_Customer::setErrorMsg(array('您的密码已重置！'));
                    $this->_helper->redirector->gotoSimple('index');
                }
            } else {
                $result = '您已经重设过密码';
            }
        } else {
            $result =  '无效链接';
        }
        
        $this->view->form = $form;
        $this->view->result = $result;
    }

    public function resetSuccessAction()
    {
        // $this->getHelper('layout')->disableLayout();
        $this->view->headTitle("修改密码成功 ");
        $email = $this->getRequest()->getParam('email');
        $pwd1 = $this->getRequest()->getParam('pwd1');
        $pwd2 = $this->getRequest()->getParam('pwd2');
        if ($pwd1 == $pwd2) {
            $res = Model_User::setPassword($email, md5($pwd1.MD5_SALT));
            //$this->_userModule->login($email, $pwd1);
            Class_Customer::login(array('email'=>$email,'password'=>md5($pwd1.MD5_SALT)));
        }
        // echo $email.' '.$pwd1.' '.$pwd2;
        echo '<script language="javascript">alert("修改密码成功!")</script>';
        //$this->getHelper('Redirector')->gotoSimple('index','index','default');
        // header("Locatcion:http://www.hj.local");
        echo '<meta http-equiv="refresh" content="0;url=http://www.pmatch.cn/">';
    }
}