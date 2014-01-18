<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends BaseController
{
    //AuIDログイン
    public function loginAction(Request $request)
    {

        if(!empty($hensu)){
            $session = $request->getSession();

            //開発モード時,セッションを生成する。
            $MODE = 'DEV';
            $user_type = 'loggedIn';


            if( $MODE == 'DEV'){
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
            }

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
             ->add('mailAddress', 'text',array('label'=>false))
             ->add('password', 'text',array('label'=>false))
             ->add('confirm', 'submit', array('label'=>'内容を確認'))
             ->getForm();
            $userData = $form->getData();
        }
        $caution = null;
        return $this->render('MachigaiGameBundle:Register:login.html.twig', array('caution'=>$caution,'form' => $form->createView()));
    }
    public function loginCheckAction(Request $request){
        $userData = new User();
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('RegisterUserComplete'))
         ->add('mailAddress', 'text',array('label'=>false))
         ->add('password', 'text',array('label'=>false))
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
        }elseif($password != $checkData[0]->getPassword()){
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

    public function indexAction(Request $request)
    {
    
	$form = $this->createFormBuilder()
	 ->setMethod('GET')
 	 ->add('nickname', 'text')
	 ->add('confirm', 'submit', array('label'=>'内容を確認'))
	 ->getForm();
        return $this->render('MachigaiGameBundle:Register:index.html.twig', array('form' => $form->createView()) );
    }

    public function completeAction(Request $request)
    { 
        $nickname = new User();

        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('nickname', 'text')
        ->add('confirm', 'submit', array('label'=>'内容を確認'))
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
        return $this->render('MachigaiGameBundle:Register:complete.html.twig');
    }
    public function confirmAction(Request $request){
       $nickname = new User();

        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('nickname', 'hidden')
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
         ->add('mailAddress', 'text',array('label'=>false))
         ->add('password', 'text',array('label'=>false))
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
        return $this->render('MachigaiGameBundle:Register:userRegister.html.twig', array('userData'=>$userData,'form' => $form->createView()) );
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

         $data = new User();
         $data->setMailAddress($userData['mailAddress']);
         $data->setPassword($userData['password']);
         $data->setNickname('ゲスト');
         $data->setCreatedAt(date("Y-m-d H:i:s"));
         $data->setUpdatedAt(date("Y-m-d H:i:s"));

         $em = $this->getDoctrine()->getEntityManager();
         $em->persist($data);
         $em->flush();

        return $this->render('MachigaiGameBundle:Register:userComplete.html.twig');
    }
    public function sentEmailAction(){
        return $this->render('MachigaiGameBundle:Register:sentEmail.html.twig');
    }
    public function beforeRegisterNicknameAction(){
        return $this->render('MachigaiGameBundle:Register:beforeRegisterNickname.html.twig');
    }

}
