<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\User;
use Machigai\GameBundle\Entity\KeyValueStore;
use Machigai\GameBundle\Entity\Log;
use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;
use \DateInterval;

class RegisterController extends BaseController
{
    //AuIDログイン
    public function loginAction(Request $request)
    {
         if(!empty($login)){
            $session = $request->getSession();

            $id = $session->get('id');
            if( empty($id) ) {
                //auIDログインページへリダイレクト
                return $this->redirect('https://auone.jp');
            }else{
                return $this->redirect($this->generateUrl('Top'));
            }
        }else{
            $form = $this->createFormBuilder()
             ->setMethod('GET')
             ->add('mailAddress', 'email',array('label'=>false))
             ->add('password', 'password',array('label'=>false))
             ->add('confirm', 'submit', array('label'=>'内容を確認'))
             ->getForm();
            $userData = $form->getData();
        }
        $caution = null;
        return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
/*
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
*/
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

        $salt = "lkjfa74uhfdou593krtbf9lsmfk1gfrjurl";
        $password = $password.$salt;
        $password = hash('sha512',$password);

        $checkData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('mailAddress'=>$mailAddress));

		$auid = null;
        if(!empty($checkData)){
            $tmpPass = $checkData[0]->getTempPass();
			$auid = $checkData[0]->getAuId();
        }else{
            $tmpPass = null;
        }
		
		
        if(empty($checkData)){
            $caution = "メールアドレスまたはパスワードが間違っています。ご確認の上、再入力をお願いします。";
            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));

        }elseif($password != $checkData[0]->getPassword()){
            $caution = "メールアドレスまたはパスワードが間違っています。ご確認の上、再入力をお願いします。";
            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
        }elseif(!empty($tmpPass)){
            $caution = "アカウントが有効化されていません。メールのURLをクリックしてアカウントを有効にしてください";
            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
        }elseif(!empty($auid)){
            $caution = "このメールアドレスは使われていません";
            return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
		} else {
                $userId = $checkData[0]->getId();
				
				$em = $this->getDoctrine()->getEntityManager();
				$user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
				
				$log = new Log();
				$log->setUserId($user->getId());
				$log->setType("login");
				$log->setName("login complete");
				$log->setCreatedAt(date("Y-m-d H:i:s"));
				$em->persist($log);
				$em->flush();
				
                $session = $request->getSession();
                //開発モード時,セッションを生成する。
                $MODE = 'DEV';
                $user_type = 'loggedIn';

				$auId = $user->getAuId();
				
				// 既にAUIDが登録されている
				if( !empty($auId) ){
                    return $this->render('MachigaiGameBundle:Register:alreadyExistAuId.html.twig');
				}
				
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

                        $request = $this->get('request');
                        $cookies = $request->cookies;

                        if(!$cookies->has('myCookie')){
                            $cookie = new Cookie('myCookie', $user->getSyncToken(), time() + 3600 * 24 * 30);
                            $response = new Response();
                            $response->headers->setCookie($cookie);
                            $response->send();
                        }

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
					return $this->redirect($this->generateUrl('AuIdLogin'));
                }
        }
    }

    public function indexAction($temp)
    {
	$form = $this->createFormBuilder()
	 ->setMethod('GET')
 	 ->add('nickname', 'text')
     ->add('tempPass','hidden')
	 ->add('confirm', 'submit', array('label'=>'内容を確認'))
	 ->getForm();
        return $this->render('MachigaiGameBundle:Register:index.html.twig', array('tempPass'=>$temp,'form' => $form->createView()) );
    }

	public function forgetPasswordAction(){
		$form = $this->createFormBuilder()
				->setMethod("GET")
				->add('mailAddress', 'text',array('label'=>"メールアドレス"))
				->getForm();
		
		$caution = null;
		return $this->render('MachigaiGameBundle:Register:forget_password.html.twig',
					array('caution'=>$caution, "form" => $form->createView())
				);
	}
	
	public function forgetPasswordConfirmAction(Request $request){
        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('mailAddress','hidden')
        ->add('confirm', 'submit')
        ->getForm();
        $form->bind($request);
        $data = $form->getData();
		$mailAddress = $data["mailAddress"];

        return $this->render('MachigaiGameBundle:Register:forget_password_confirm.html.twig',
				array('mailAddress'=>$mailAddress,'form' => $form->createView()));
	}

	public function forgetPasswordSendAction(Request $request){
		
//		$mailAddress = $request->request->get("mailAddress");
		$tmp = $request->request->all();
		$mailAddress = $tmp["form"]["mailAddress"];
		
		// ユーザーの検索
        $checkData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('mailAddress'=>$mailAddress));

        $resetPasswordActivationCode = hash('sha512', 'forgetPassword' . $mailAddress . uniqid( mt_rand(), true ));
		
		// パスワードの生成
		for ($i = 0, $str = null; $i < 6; ) { 
			$num = mt_rand(0x30, 0x7A); // ASCII文字コード 
			if ((0x30 <= $num && $num <= 0x39) || (0x41 <= $num && $num <= 0x5A) 
			|| (0x61 <= $num && $num <= 0x7A)) { 
				$str .= chr($num); // 文字コードを文字に変換 
				$i++; 
			} 
		} 
        //ユーザが見つからない場合もメールを送信のフリをする
		if(empty($checkData)){
            return $this->render('MachigaiGameBundle:Register:forget_password_send.html.twig');
        }

        $user = $checkData[0];
		$password = $str;

        //新パスワードの保存
        $now = new DateTime();
        $validTo = $now->add( new DateInterval("P1D") );

        $informationAboutResetPassword = array(
            'userId' => $user->getId(),
            'newPassword' => $password,
            'validTo' => $validTo,
            );
        $informationAboutResetPasswordJsonString = json_encode($informationAboutResetPassword);

        $em = $this->getDoctrine()->getManager();
        $store = new KeyValueStore();
        $store->setKeycode($resetPasswordActivationCode);
        $store->setvalue($informationAboutResetPasswordJsonString);

        $em->persist($store);
        $em->flush();
		
		// パスワードのメール送信
         $message = \Swift_Message::newInstance()
        ->setSubject('【まちがいさがし放題】パスワード再発行')
        ->setFrom('regist@machigai.puzzle-m.net')
        ->setTo($user->getMailAddress())
        ->setBody("本メールは「スタンプ付き♪まちがいさがし放題for auスマートパス」でパスワード再発行をされたお客様へお送りしています。\n".
				"\n".
				"新しいパスワード：" .$str.
				"\n\n".
                "尚、このメールに心当たりのない方は破棄していただきますようお願い申し上げます。\n\n".
                "下記URLをクリックすると仮パスワードの登録が完了します。\n".
                "その後、TOPページより新しいパスワードで再度ログインをお願い致します。\n\n".
                "https://machigai.puzzle-m.net/forget_password_complete/".$resetPasswordActivationCode.
                "\n\n".
				"※ログイン後、TOPの「ヘルプ」→「ユーザー登録について」→「パスワード変更」より、パスワードを変更していただくことをお勧めします。".
				"\n".
				"https://machigai.puzzle-m.net\n".
				"\n".
				"※このメールアドレスは配信専用です。返信されないようお願いいたします。"
/*            $this->renderView(
                'HelloBundle:Hello:email.txt.twig',
                array('name' => $name)
                    )
*/                )
            ;
		try {
         $this->get('mailer')->send($message);
		} catch( Exception $ex ){
		} 
        return $this->render('MachigaiGameBundle:Register:forget_password_send.html.twig');
	}
	
	public function forgetPasswordCompleteAction($forgetPasswordActivationCode, Request $request){
        $em = $this->getDoctrine()->getManager();

        $stores = $this->getDoctrine()->getRepository('MachigaiGameBundle:KeyValueStore')->findBy(array('keycode'=>$forgetPasswordActivationCode));

        //アクティベーションコードが見つからない場合
        if(empty($stores)){
            return $this->render('MachigaiGameBundle:Setting:changeEmailActivationError.html.twig');
        }

        //アクティベーションコードが見つかった場合
        $store = $stores[0];
        $informationAboutResetPasswordJsonString = $store->getValue();

        $info = json_decode($informationAboutResetPasswordJsonString);
        $userId = $info->userId;
        $newPassword = $info->newPassword;
        $validTo =new DateTime($info->validTo->date);
        $now = new DateTime();

        //有効期間が切れている場合
        if( $now > $validTo ){
            $em->remove($store);
            $em->flush();
            return $this->render('MachigaiGameBundle:Setting:changeEmailActivationError.html.twig');
        }

        //正常処理
        $salt = "lkjfa74uhfdou593krtbf9lsmfk1gfrjurl";
        $newPassword = $newPassword.$salt;
        $newPassword = hash('sha512',$newPassword);

        $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
        $user->setPassword($newPassword);
        $em->remove($store);
        $em->persist($user);
        $em->flush();

        return $this->render('MachigaiGameBundle:Register:forget_password_complete.html.twig');
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
        $userData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('tempPass'=>$tempPass));

         $user = $userData[0];
         $userId = $user->getId();

         $em = $this->getDoctrine()->getEntityManager();
         $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
         $user->setNickname($nickname);
         $user->setTempPass(null);
         $em->flush();
		 
		 $log = new Log();
		 $log->setUserId($user->getId());
		 $log->setType("register");
		 $log->setName("nickname setting complete");
		 $log->setCreatedAt(date("Y-m-d H:i:s"));
		 $em->persist($log);
		 $em->flush();	 

         $email = $user->getMailAddress();
         $pass = $user->getPassword();

		if( false ){						
			// リワード
			$cid = "6250";					
			$ad  = "install";					
			$uid = hash('sha256',$user->getAuId());
			$key = "8ccc6ee910d93df31a1e48b542724e5b";

			$to_digest = "$ad:$cid:$uid:$key";
			$digest = hash('sha256', $to_digest);

			return $this->render('MachigaiGameBundle:Register:complete.html.twig',
					array( 'syncToken'=> $user->getSyncToken(), 'email'=>$email,'pass'=>$pass,
						'cid'=>$cid, 'ad' => $ad, 'uid' => $uid, 'digest' => $digest, 'reword' => true ) );
		
		} else {

			return $this->render('MachigaiGameBundle:Register:complete.html.twig',
					array( 'syncToken'=> $user->getSyncToken(), 'email'=>$email,'pass'=>$pass, 'reword' => false) );
			
		}
		

    }
    public function confirmAction(Request $request){
       $nickname = new User();
       //$session = $this->get("query")->getSession();
       //$syncTokenPre = $sesion->get("syncTokenPre");
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

		 // 24時間経過している仮登録ユーザを削除
         if(!empty($emailCheck)){
			$tp = $emailCheck[0]->getTempPass();
			if(!empty($tp)){
			 
				$from = $emailCheck[0]->getCreatedAt();
				$from = ($from->format('Y-m-d H:i:s'));
				$to = date("Y-m-d H:i:s", time());
				$fromSec = strtotime($from);
				$toSec   = strtotime($to);
				$differences = $toSec - $fromSec;

				if($differences > 86400){
					$em = $this->getDoctrine()->getEntityManager();
					$user = $em->getRepository('MachigaiGameBundle:User')->find($emailCheck[0]->getId());
					$em->remove($user);
					$em->flush();	
					$emailCheck = null;
				}
			}
		 }
		 
         if(!empty($emailCheck)){
             $form = $this->createFormBuilder()
             ->setMethod('GET')
             ->setAction($this->generateUrl('RegisterUserConfirm'))
             ->add('mailAddress', 'text',array('label'=>false))
             ->add('password', 'password',array('label'=>false))
             ->add('confirm', 'submit', array('label'=>'内容を確認'))
             ->getForm();
            $error = "入力されたメールアドレスはすでに使用されています";
            return $this->render('MachigaiGameBundle:Register:userRegister.html.twig',array('error'=>$error,'form' => $form->createView()));
         }

        return $this->render('MachigaiGameBundle:Register:userConfirm.html.twig',array('userData'=>$userData,'form' => $form->createView()));
    }
    public function userCompleteAction(Request $request){
        $salt = "lkjfa74uhfdou593krtbf9lsmfk1gfrjurl";
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->add('mailAddress', 'hidden',array('label'=>false))
         ->add('password', 'hidden',array('label'=>false))
         ->getForm();
         $form->bind($request);
         $userData = $form->getData();
         $password = $userData['password'].$salt;
         $password = hash('sha512',$password);
         $tempData = hash('sha512',date("Y-m-d H:i:s").$salt);
         $syncToken = uniqid();

         $data = new User();
         $data->setMailAddress($userData['mailAddress']);
         $data->setPassword($password);
         $data->setSyncToken($syncToken);
         $data->setCreatedAt(date("Y-m-d H:i:s"));
         $data->setUpdatedAt(date("Y-m-d H:i:s"));
         $data->setTempPass($tempData);

         $message = \Swift_Message::newInstance()
        ->setSubject('【まちがいさがし放題】会員登録のご案内')
        ->setFrom('regist@machigai.puzzle-m.net')
        ->setTo($userData['mailAddress'])
        ->setBody("本メールは「スタンプ付き♪まちがいさがし放題for auスマートパス」で会員登録をされるお客様へお送りしています。\nこのメールを受信された時点では登録は完了しておりませんので、ご注意下さい。\n
尚、このメールに心当たりのない方は破棄していただきますようお願い申し上げます。\n
下記URLをクリックすると登録が完了します。その後、ニックネームの登録画面に進みますので画面の案内に従って登録をお願い致します。\n\n".
"https://machigai.puzzle-m.net/register/beforeRegisterNickname/".$tempData.
"\n※URL有効期限：メール配信後24時間※\n有効期限を過ぎると登録が行えません。\n
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
		 
		try {
	        $this->get('mailer')->send($message);
		} catch (Exception $ex){
		}
		
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

            $tempPass = $checkPass[0]->getTempPass();
            if(empty($tempPass)){
                return $this->render('MachigaiGameBundle:Register:emailTimeover.html.twig');
            }

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
        return $this->render('MachigaiGameBundle:Register:emailTimeover.html.twig');
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
        $syncToken = null;

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

                    return $this->render('MachigaiGameBundle:Android:afterAuIdLogin.html.twig', array('syncToken'=> $syncToken) );

//                    return $this->redirect($this->generateUrl('Top'));
                }
    }
}
