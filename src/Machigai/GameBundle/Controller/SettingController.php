<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Machigai\GameBundle\Entity\User;
use Machigai\GameBundle\Form\UserType;

class SettingController extends Controller
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
		 ->add('nickname', 'text',array('label'=>' '))
		 ->add('confirm', 'submit', array('label'=>'確認'))
		 ->getForm();
	$form->handleRequest($request);		
	if( $form->get('confirm')->isClicked() ){
	 $data = $form->getData();
	 $form = $this->createFormBuilder()
		 ->setAction($this->generateUrl('SettingNicknameregister'))
		 ->setMethod('POST')
		 ->add('nickname','text',array('label'=>' ', 'attr'=>array('disabled'=>'disabled')))
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
   
    public function nicknameRegisterAction(){
	$request = $this->getRequest();
        $form = $this->createFormBuilder()
		 ->add('nickname')
		 ->add('register', 'submit')
		 ->getForm();
	$form->handleRequest($request);		
	 #TODO: Deal with nickname registration.
	if ( $form->get('register')->isClicked() && $form->isValid()){
		return $this->redirect($this->generateUrl('SettingComplete'));
	}
    }
   
    public function completeAction()
    {
        return $this->render('MachigaiGameBundle:Setting:complete.html.twig');
    }

}
