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
		 ->add('nickname', 'text',array('label'=>' ', 'attr'=>array('style'=>"margin-top:2%;font-size:2em;background-image:url(/bundles/machigaigame/images/parts/nicknametextarea.png);background-size:100% 100%; background-repeat:no-repeat;")))
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

}
