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

    	$form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('ChangeEmailConfirm'))
         ->add('mailAddress', 'text',array('label'=>false))
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
*/         	return $this->render('MachigaiGameBundle:Setting:changeEmail.html.twig',array('form' => $form->createView()));
             	
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
         return $this->render('MachigaiGameBundle:Setting:changeEmailConfirm.html.twig',array('newEmail'=>$newEmail,'form' => $form->createView()));
    }
    public function changeEmailSentAction(Request $request){
    	$form = $this->createFormBuilder()
         ->setMethod('GET')
         ->add('mailAddress', 'hidden',array('label'=>false))
         ->getForm();
         $form->bind($request);
         $newEmail = $form->getData();
    	/*
    	sending an email function
    	*/
    	return $this->render('MachigaiGameBundle:Setting:changeEmailSent.html.twig',array('form' => $form->createView()));
    }
    public function changeEmailCompleteAction(){
		$pre_userId = $this->getDoctrine()
         ->getRepository('MachigaiGameBundle:User')
         ->findAll();
    	$userId = $pre_userId[0]->getId();

    	$em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
        $user->setMailAddress($email);
        $em->flush();
    	return $this->render('MachigaiGameBundle:Setting:changeEmailComplete.html.twig');
    }
    public function changePasswordAction(){
        $form = $this->createFormBuilder()
         ->setMethod('GET')
         ->setAction($this->generateUrl('ChangePasswordConfirm'))
         ->add('password', 'text',array('label'=>false))
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

         $em = $this->getDoctrine()->getEntityManager();
         $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
         $user->setPassword($password['password']);
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
    	$pre_userId = $this->getUser();
        $userId = $pre_userId->getId();
    	$session = $request->getSession();
        $id = $session->get('id');
        if(!empty($id)){
	        $session->remove('id');
        }
        $em = $this->getDoctrine()->getEntityManager();
         $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
         $em->remove($user);
         $em->flush();

    	return $this->render('MachigaiGameBundle:Setting:deleteUserComplete.html.twig');
    }
}
