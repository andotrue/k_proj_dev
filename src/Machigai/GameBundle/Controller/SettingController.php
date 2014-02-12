<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Machigai\GameBundle\Entity\User;
use Machigai\GameBundle\Form\UserType;

class SettingController extends BaseController
{
    public function indexAction()
    {
	return $this->render('MachigaiGameBundle:Setting:index.html.twig');
    }

    public function nicknameAction()
    {
	$request = $this->getRequest();
        $form = $this->createFormBuilder()
		 ->setMethod('GET')
		 ->add('nickname', 'text',array('label'=>' ', 'attr'=>array('style'=>"margin-top:2%;font-size:1.5em;background-image:url(/bundles/machigaigame/images/parts/nicknametextarea.png);background-size:100% 100%; background-repeat:no-repeat;")))
		 ->add('confirm', 'submit', array('label'=>'確認'))
		 ->getForm();
	$form->handleRequest($request);
	if( $form->get('confirm')->isClicked() ){
	 $data = $form->getData();
	 $form = $this->createFormBuilder()
		 ->setAction($this->generateUrl('SettingNicknameregister'))
		 ->setMethod('POST')
//		 ->add('nickname','text',array('label'=>' ', 'attr'=>array('disabled'=>'disabled')))
		 ->add('nickname','hidden',array('label'=>' '))
		 ->add('register', 'submit', array('label'=>'登録'))
		 ->add('ammend','button',array('label'=>'修正', 'attr'=>array('onclick'=>'history.back()')))
		 ->getForm();
	 $form->setData($data);
         return $this->render('MachigaiGameBundle:Setting:confirm.html.twig', array('form' => $form->createView()) );
	}else{
	// 通常のGETリクエスト
         return $this->render('MachigaiGameBundle:Setting:nickname.html.twig', array('form' => $form->createView()) );
	}
    }

    public function nicknameRegisterAction(Request $request){
    $nickname = new User();
	$request = $this->getRequest();
    $form = $this->createFormBuilder()
	     ->setMethod('GET')
		 ->add('nickname','text')
		 ->add('register', 'submit')
		 ->getForm();
	$form->bind($request);
	$nickname = $form->getData();
    $nickname = $nickname['nickname'];
    $pre_userId = $this->getUser();
    $userId = $pre_userId->getId();
    $em = $this->getDoctrine()->getEntityManager();
 	$user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
 	$user->setNickName($nickname);
 	$em->flush();
		return $this->redirect($this->generateUrl('SettingComplete'));
    }

    public function completeAction()
    {
        return $this->render('MachigaiGameBundle:Setting:complete.html.twig');
    }
    public function userSettingAction(){
    	return $this->render('MachigaiGameBundle:Setting:userSetting.html.twig');
    }
    public function changeEmailAction(){
    	$pre_userId = $this->getUser();
	    $userId = $pre_userId->getId();
        $caution = null;

    	$form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('ChangeEmailConfirm'))
         ->add('mailAddress', 'email',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
 /*       $form->handleRequest($request);
      if( $form->get('confirm')->isClicked() ){
        $data = $form->getData();
	 	$form = $this->createFormBuilder()
		 ->setAction($this->generateUrl('changeEmailConfirm'))
		 ->setMethod('POST')
		 ->add('mailAddress','hidden',array('label'=>false))
		 ->add('register', 'submit', array('label'=>'登録'))
		 ->getForm();
	    $form->setData($data);
	    $form->bind($request);
        $newEmail = $form->getData();
         	return $this->render('MachigaiGameBundle:Setting:changeEmailConfirm.html.twig', array('form' => $form->createView()));
         }else{
*/         	return $this->render('MachigaiGameBundle:Setting:changeEmail.html.twig',array('caution'=>$caution,'form' => $form->createView()));

    }
    public function changeEmailConfirmAction(Request $request){
         $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('ChangeEmailSent'))
         ->add('mailAddress', 'hidden',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
         $form->bind($request);
         $newEmail = $form->getData();

        $userData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findBy(array('mailAddress'=>$newEmail['mailAddress']));

        if(!empty($userData)){
            $caution = "このメールアドレスはすでに使われています。";
            return $this->render('MachigaiGameBundle:Setting:changeEmail.html.twig',array('caution'=>$caution,'form' => $form->createView()));
        }else{
            $newEmail = $newEmail['mailAddress'];
            return $this->render('MachigaiGameBundle:Setting:changeEmailConfirm.html.twig',array('newEmail'=>$newEmail,'form' => $form->createView()));
        }
    }
    public function changeEmailSentAction(Request $request){
    	$form = $this->createFormBuilder()
         ->setMethod('GET')
         ->add('mailAddress', 'hidden',array('label'=>false))
         ->getForm();
         $form->bind($request);
         $newEmail = $form->getData();

         $salt = "adsofaief048u49wtuhlkmfgosaihfguaeaisdufgha8yw";
         $tempData = hash('sha512',$salt.$newEmail['mailAddress']);
         $message = \Swift_Message::newInstance()
        ->setSubject('【まちがいさがし放題】メールアドレス変更のご案内')
        ->setFrom('machigai.puzzle-m.net')
        ->setTo($newEmail['mailAddress'])
        ->setBody("本メールは「スタンプ付き♪まちがいさがし放題for auスマートパス」でメールアドレスを変更されるお客様へお送りしています。このメールを受信された時点では変更は完了しておりませんので、ご注意下さい。\n
尚、このメールに心当たりのない方は破棄していただきますようお願い申し上げます。\n
下記URLをクリックするとメールアドレス変更が完了します。\n
その後、TOPページより新しいメールアドレスで再度ログインをお願い致します。\n\n".
"http://st.machigai.puzzle-m.net/app_dev.php/setting/changeEmailComplete/".$tempData.
"\n※URL有効期限：メール配信後24時間※有効期限を過ぎると登録が行えません。\n
お手数ですがはじめからやり直してください。今後とも「まちがいさがし放題」をどうぞよろしくお願いいたします。\n
https://machigai.puzzle-m.net\n
\n
＿＿＿＿＿＿＿＿＿＿＿＿＿＿\n
※このメールアドレスは配信専用です。返信されないようお願いいたします。"
                )
            ;
            $this->get('mailer')->send($message);

         $pre_userId = $this->getUser();
         $userId = $pre_userId->getId();

         $em = $this->getDoctrine()->getEntityManager();
         $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
         $user->setTempPass($newEmail['mailAddress']);
         $em->flush();

    	return $this->render('MachigaiGameBundle:Setting:changeEmailSent.html.twig',array('form' => $form->createView()));
    }
    public function changeEmailCompleteAction($pass){
        $salt = "adsofaief048u49wtuhlkmfgosaihfguaeaisdufgha8yw";
        $check = array();
        $userData = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findAll();

        for ($i=0; $i < count($userData);$i++) {
            if($pass == hash('sha512',$salt.$userData[$i]->getTempPass())){
                $userId = $userData[$i]->getId();
                $newEmail = $userData[$i]->getTempPass();
            }
        }

    	$em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
        $user->setMailAddress($newEmail);
        $em->flush();
    	return $this->render('MachigaiGameBundle:Setting:changeEmailComplete.html.twig');
    }
    public function changePasswordAction(){
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('ChangePasswordConfirm'))
         ->add('password', 'password',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
    	return $this->render('MachigaiGameBundle:Setting:changePassword.html.twig',array('form' => $form->createView()));
    }
    public function changePasswordConfirmAction(Request $request){
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('ChangePasswordComplete'))
         ->add('password', 'hidden',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
         $form->bind($request);
         $password = $form->getData();

        return $this->render('MachigaiGameBundle:Setting:changePasswordConfirm.html.twig',array('form' => $form->createView()));
    }
    public function changePasswordCompleteAction(Request $request){
        $pre_userId = $this->getUser();
        $userId = $pre_userId->getId();
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->add('password', 'hidden',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
         $form->bind($request);
         $password = $form->getData();
         $password = $password['password'];

         $salt = "lkjfa74uhfdou593krtbf9lsmfk1gfrjurl";
         $password = $password.$salt;
         $password = hash('sha512',$password);

         $em = $this->getDoctrine()->getEntityManager();
         $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
         $user->setPassword($password);
         $em->flush();
        return $this->render('MachigaiGameBundle:Setting:changePasswordComplete.html.twig');
    }
    public function deleteUserAction(){
    	return $this->render('MachigaiGameBundle:Setting:deleteUser.html.twig');
    }
    public function deleteUserConfirmAction(){
    	return $this->render('MachigaiGameBundle:Setting:deleteUserConfirm.html.twig');
    }
    public function deleteUserCompleteAction(Request $request){
    	$user = $this->getUser();
        if(empty($user)){
            return $this->render('MachigaiGameBundle:Setting:deleteUserComplete.html.twig');
        }else{
            $userId = $user->getId();

            //PlayHistory id取得
            $playHistories = $this->getDoctrine()
             ->getRepository('MachigaiGameBundle:PlayHistory')
             ->findBy(array('user'=>$user));

            if(!empty($playHistories)){
                $playHistoryId = array();
                foreach ($playHistories as $playHistory) {
                    $playHistoryId[] = $playHistory->getId();
                }
    /*
                //TODO: question_playhistoryテーブル 不要であれば削除

                $question_playhistories = $this->getDoctrine()
                 ->getRepository('MachigaiGameBundle:question_playhistory')
                 ->findBy(array('playhistory_id'=>$playHistoryId));

                //レコードに対象のplayhistory_idが存在する場合 該当のquestion_playhistory削除
                if(!empty($question_playhistories)){
                    foreach ($question_playhistories as $qp) {
                        $em = $this->getDoctrine()->getEntityManager();
                        $data = $em->getRepository('MachigaiGameBundle:question_playhistory')->find($qp->getId());
                        $em->remove($data);
                        $em->flush();
                    }
                }
    */            //PlayHistory 削除
                foreach ($playHistoryId as $play_id) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $data = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($play_id);
                    $em->remove($data);
                    $em->flush();
                }
            }

            $purchaseHistories = $this->getDoctrine()
             ->getRepository('MachigaiGameBundle:PurchaseHistory')
             ->findBy(array('user'=>$user));

            //PurchaseHistory 削除
            if(!empty($purchaseHistories)){
                $purchaseHistoryId = array();
                foreach ($purchaseHistories as $p_h) {
                    $purchaseHistoryId[] = $p_h->getId();
                }

                foreach ($purchaseHistoryId as $purchase_id) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $data = $em->getRepository('MachigaiGameBundle:PurchaseHistory')->find($purchase_id);
                    $em->remove($data);
                    $em->flush();
                }
            }

            $rankings = $this->getDoctrine()
             ->getRepository('MachigaiGameBundle:Ranking')
             ->findBy(array('user'=>$user));

            //Ranking 削除
            if(!empty($rankings)){
                $rankingId = array();
                foreach ($rankings as $ranking) {
                    $rankingId[] = $ranking->getId();
                }

                foreach ($rankingId as $rank) {
                    $em = $this->getDoctrine()->getEntityManager();
                    $data = $em->getRepository('MachigaiGameBundle:Ranking')->find($rank);
                    $em->remove($data);
                    $em->flush();
                }
            }

            //ログアウト
        	$session = $request->getSession();
            $session->remove('id');
            $session->remove("syncToken");

            //ユーザー削除
            $em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
            $em->remove($user);
            $em->flush();

        	return $this->render('MachigaiGameBundle:Setting:deleteUserComplete.html.twig');
        }
    }

}
