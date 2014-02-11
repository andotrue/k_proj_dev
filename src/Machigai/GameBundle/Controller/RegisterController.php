<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends BaseController
{
    //AuIDログイン
    public function loginAction(Request $request)
    {
        $logger = $this->get('logger');
        $logger = $logger->info('RegisterControloginAction');
        //プロダクションモードのとき
        if ($this->MODE != $this->DEBUG){
            $this->redirect($this->generateUrl('AuIdLogin'));
        }

        //
        $session = $request->getSession();

        //開発モード時,セッションを生成する。
        $user_type = 'loggedIn';


        if($user_type == 'loggedIn'){
            //ログインユーザの場合
            $session->set('auId', 'auid1');
            $session->set('id', '167');
            $session->set('smartPassResult', true );
        }elseif($user_type == 'notLoggedIn'){
            //非ログインユーザの場合
            $session->set('auId', 'auid1');
            $session->set('id', null );
            $session->set('smartPassResult', true );
        }else{
            $session->set('auId', null );
            $session->set('id', null );
            $session->set('smartPassResult', null );
        }

        $id = $session->get('id');
        if( empty($id) ) {
            //auIDログインページへリダイレクト
            return $this->redirect('https://auone.jp');
        }else{
            return $this->redirect($this->generateUrl('Top'));
        }
//        return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
    }
    public function loginCheckAction(Request $request){
        $userData = new User();
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('RegisterUserComplete'))
         ->add('mailAddress', 'email',array('label'=>false))
         ->add('password', 'password',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
        $form->bind($request);
        $userData = $form->getData();
        $mailAddress = $userData['mailAddress'];
        $password = $userData['password'];

        $checkData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('mailAddress'=>$mailAddress));
        if(empty($checkData)){
            $caution = "メールアドレスまたはパスワードが間違っています。ご確認の上、再入力をお願いします。";
            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
//        }elseif($checkData[0]->getNickname()==NULL){
//            $caution = "登録が完了していません。";
//            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
        }elseif(hash('sha512',$password) != $checkData[0]->getPassword()){
            $caution = "メールアドレスまたはパスワードが間違っています。ご確認の上、再入力をお願いします。";
            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
        }else{
                $userId = $checkData[0]->getId();
                $session = $request->getSession();
                //開発モード時,セッションを生成する。
                $MODE = 'DEV';
                $user_type = 'loggedIn';

                if( $MODE == 'DEV'){
                    if($user_type == 'loggedIn'){
                        //ログインユーザの場合
                        $session->set('auId', 'auid1');
                        $session->set('id', $userId);
                        $session->set('smartPassResult', true );
                        //クッキー生成
                        $syncToken = uniqid();

                        $em = $this->getDoctrine()->getEntityManager();
                        $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
                        $user->setSyncToken($syncToken);
                        $em->flush();

                        $cookie = new Cookie('myCookie', $syncToken);
                        $response = new Response();
                        $response->headers->setCookie($cookie);
                        $response->send();

                    }elseif($user_type == 'notLoggedIn'){
                        //非ログインユーザの場合
                        $session->set('auId', 'auid1');
                        $session->set('id', null );
                        $session->set('smartPassResult', true );
                    }else{
                        $session->set('auId', null );
                        $session->set('id', null );
                        $session->set('smartPassResult', null );
                    }
                }
                $id = $session->get('id');
                if( empty($id) ) {
                    //auIDログインページへリダイレクト
                    return $this->redirect('https://auone.jp');
                }else{
                    return $this->redirect($this->generateUrl('Top'));
                }
        }
    }

    public function indexAction()
    {
	$form = $this->createFormBuilder()
	 ->setMethod('GET')
 	 ->add('nickname', 'text')
     ->add('tempPass','hidden')
	 ->add('confirm', 'submit', array('label'=>'内容を確認'))
	 ->getForm();
        return $this->render('MachigaiGameBundle:Register:index.html.twig', array('tempPass'=>"test",'form' => $form->createView()) );
    }

    public function completeAction(Request $request)
    {
        $nickname = new User();

        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('nickname', 'text')
        ->add('tempPass','hidden')
        ->add('confirm', 'submit', array('label'=>'内容を確認'))
        ->getForm();
        $form->bind($request);
        $nickname = $form->getData();
        $nickname = $nickname['nickname'];
        $tempPass = $form->getData();
		$tempPass = $tempPass['tempPass'];

//        $pre_userId = $this->getUser();
//        $userId = $pre_userId->getId();

         $em = $this->getDoctrine()->getEntityManager();
         $user = $em->getRepository('MachigaiGameBundle:User')->findBy(array('tempPass'=>$tempPass));
         $user[0]->setNickname($nickname);
         $em->flush();

         $userData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('tempPass'=>$tempPass));

         $email = $userData[0]->getMailAddress();
         $pass = $userData[0]->getPassword();

        return $this->render('MachigaiGameBundle:Register:complete.html.twig',array('email'=>$email,'pass'=>$pass));
    }
    public function confirmAction(Request $request){
       $nickname = new User();
       $session = $this->get("query")->getSession();
       $syncTokenPre = $sesion->get("syncTokenPre");
        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('nickname', 'hidden')
        ->add('tempPass','hidden')
        ->add('confirm', 'submit')
        ->getForm();
        $form->bind($request);
        $nickname = $form->getData();

        return $this->render('MachigaiGameBundle:Register:confirm.html.twig',array('nickname'=>$nickname,'form' => $form->createView()));
    }
    public function userRegisterAction(Request $request){
        $userData = new User();

        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('RegisterUserConfirm'))
         ->add('mailAddress', 'email',array('label'=>false))
         ->add('password', 'password',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();

         $error = null;
        return $this->render('MachigaiGameBundle:Register:userRegister.html.twig', array('error'=>$error,'userData'=>$userData,'form' => $form->createView()) );
    }
    public function userConfirmAction(Request $request){
        $userData = new User();

        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('RegisterUserComplete'))
         ->add('mailAddress', 'hidden',array('label'=>false))
         ->add('password', 'hidden',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
         $form->bind($request);
         $userData = $form->getData();

         $emailCheck = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('mailAddress'=>$userData['mailAddress']));

         if(!empty($emailCheck)){
             $form = $this->createFormBuilder()
             ->setMethod('GET')
             ->setAction($this->generateUrl('RegisterUserConfirm'))
             ->add('mailAddress', 'text',array('label'=>false))
             ->add('password', 'text',array('label'=>false))
             ->add('confirm', 'submit', array('label'=>'内容を確認'))
             ->getForm();
            $error = "入力されたメールアドレスはすでに使用されています";
;            return $this->render('MachigaiGameBundle:Register:userRegister.html.twig',array('error'=>$error,'form' => $form->createView()));
         }

        return $this->render('MachigaiGameBundle:Register:userConfirm.html.twig',array('userData'=>$userData,'form' => $form->createView()));
    }
    public function userCompleteAction(Request $request){

        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->add('mailAddress', 'hidden',array('label'=>false))
         ->add('password', 'hidden',array('label'=>false))
         ->getForm();
         $form->bind($request);
         $userData = $form->getData();
         $userData['password'] = hash('sha512',$userData['password']);
         $salt = "akjsfoaeouawoa892ah4lkja78aklalkajgarglskr";
         $tempData = hash('sha512',date("Y-m-d H:i:s").$salt);

         $data = new User();
         $data->setMailAddress($userData['mailAddress']);
         $data->setPassword($userData['password']);
         $data->setCreatedAt(date("Y-m-d H:i:s"));
         $data->setUpdatedAt(date("Y-m-d H:i:s"));
         $data->setTempPass($tempData);

         $message = \Swift_Message::newInstance()
        ->setSubject('【まちがいさがし放題】会員登録のご案内')
        ->setFrom('machigai.puzzle-m.net')
        ->setTo($userData['mailAddress'])
        ->setBody("本メールは「スタンプ付き♪まちがいさがし放題for auスマートパス」で会員登録をされるお客様へお送りしています。\nこのメールを受信された時点では登録は完了しておりませんので、ご注意下さい。\n
尚、このメールに心当たりのない方は破棄していただきますようお願い申し上げます。\n
下記URLをクリックすると登録が完了します。その後、ニックネームの登録画面に進みますので画面の案内に従って登録をお願い致します。\n\n".
"http://st.machigai.puzzle-m.net/app_dev.php/register/beforeRegisterNickname/".$tempData.
"\n※URL有効期限：メール配信後24時間※有効期限を過ぎると登録が行えません。\n
お手数ですがはじめからやり直してください。今後とも「まちがいさがし放題」をどうぞよろしくお願いいたします。\n
https://machigai.puzzle-m.net\n
\n
＿＿＿＿＿＿＿＿＿＿＿＿＿＿\n
※このメールアドレスは配信専用です。返信されないようお願いいたします。"
/*            $this->renderView(
                'HelloBundle:Hello:email.txt.twig',
                array('name' => $name)
                    )
*/                )
            ;
         $this->get('mailer')->send($message);

         $em = $this->getDoctrine()->getEntityManager();
         $em->persist($data);
         $em->flush();

        return $this->render('MachigaiGameBundle:Register:userComplete.html.twig');
    }
    public function sentEmailAction(){
        return $this->render('MachigaiGameBundle:Register:sentEmail.html.twig');
    }

    // au ID Login では使用されない /
    public function beforeRegisterNicknameAction($pass){
        $tempPasswords = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findAll();
         $check = array();
        foreach ($tempPasswords as $pw) {
            $check[] = $pw->getTempPass();
        }
        if(in_array($pass,$check)){
            $checkPass = $this->getDoctrine()
             ->getRepository('MachigaiGameBundle:User')
             ->findBy(array('tempPass'=>$pass));

            $from = $checkPass[0]->getCreatedAt();
            $from = ($from->format('Y-m-d H:i:s'));
            $to = date("Y-m-d H:i:s", time());
            $fromSec = strtotime($from);
            $toSec   = strtotime($to);
            $differences = $toSec - $fromSec;

            if($differences > 86400){
                $em = $this->getDoctrine()->getEntityManager();
                $user = $em->getRepository('MachigaiGameBundle:User')->find($checkPass[0]->getId());
                $em->remove($user);
                $em->flush();
                return $this->render('MachigaiGameBundle:Register:emailTimeover.html.twig');
            }
            return $this->render('MachigaiGameBundle:Register:beforeRegisterNickname.html.twig',array('tempPass'=>$pass));
        }
        return $this->render('MachigaiGameBundle:Register:authError.html.twig');
    }
    // au ID Login では使用されない /
    public function reissuePasswordAction(){
        return $this->render('MachigaiGameBundle:Register:reissuePassword.html.twig');
    }
    // au ID Login では使用されない /
    public function sendEmailAction(){
        return $this->render('MachigaiGameBundle:Register:reissuePassword.html.twig');
    }
    // au ID Login では使用されない /
    public function emailTimeoverAction(){
        return $this->render('MachigaiGameBundle:Register:emailTimeover.html.twig');
    }
    public function loginAfterSettingNameAction($email,$pass, Request $request){
        $mailAddress = $email;
        $password = $pass;

        $checkData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('mailAddress'=>$mailAddress));

                $userId = $checkData[0]->getId();
                $session = $request->getSession();
                //開発モード時,セッションを生成する。
                $MODE = 'DEV';
                $user_type = 'loggedIn';

                if( $MODE == 'DEV'){
                    if($user_type == 'loggedIn'){
                        //ログインユーザの場合
                        $session->set('auId', 'auid1');
                        $session->set('id', $userId);
                        $session->set('smartPassResult', true );
                    }elseif($user_type == 'notLoggedIn'){
                        //非ログインユーザの場合
                        $session->set('auId', 'auid1');
                        $session->set('id', null );
                        $session->set('smartPassResult', true );
                    }else{
                        $session->set('auId', null );
                        $session->set('id', null );
                        $session->set('smartPassResult', null );
                    }
                }
                $id = $session->get('id');
                if( empty($id) ) {
                    //auIDログインページへリダイレクト
                    return $this->redirect('https://auone.jp');
                }else{
                    return $this->redirect($this->generateUrl('Top'));
                }
    }
}
