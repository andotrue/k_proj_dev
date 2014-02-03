<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;

class HelpController extends BaseController
{
    public function indexAction()
    {
    	$user = $this->getUser();

		return $this->render('MachigaiGameBundle:Help:index.html.twig',array('user'=>$user));
    }

    public function howtoplayAction()
    {
	return $this->render('MachigaiGameBundle:Help:howtoplay.html.twig');
    }

    public function termsAction()
    {
	return $this->render('MachigaiGameBundle:Help:terms.html.twig');
    }

    public function formAction()
    {
	$request = $this->getRequest();
        $form = $this->createFormBuilder()
		 ->setMethod('GET')
		 ->add('title', 'text',array('label'=>false))
		 ->add('email', 'email',array('label'=>false))
		 ->add('content', 'textarea',array('label'=>false))
		 ->add('confirm', 'submit', array('label'=>'入力内容確認'))
		 ->getForm();
	$form->handleRequest($request);		
	if( $form->get('confirm')->isClicked() ){
	 $data = $form->getData();
	 $form = $this->createFormBuilder()
		 ->setAction($this->generateUrl('HelpInquiryFormDo'))
		 ->setMethod('POST')
		 ->add('title', 'hidden',array('label'=>false))
		 ->add('email', 'hidden',array('label'=>false))
		 ->add('content', 'hidden',array('label'=>false))
		 ->add('do', 'submit', array('label'=>'登録'))
		 ->add('ammend','button',array('label'=>'修正', 'attr'=>array('onclick'=>'history.back()')))
		 ->getForm();
	 $form->setData($data);
	 $form->bind($request);
     $inquiryData = $form->getData();
         return $this->render('MachigaiGameBundle:Help:confirm.html.twig', array('form' => $form->createView(),'inquiryData'=>$inquiryData) );
	}else{
	// 通常のGETリクエスト
         return $this->render('MachigaiGameBundle:Help:form.html.twig', array('form' => $form->createView()) );
	}
    }
    
    public function inquiryFormDoAction(){
	$request = $this->getRequest();
        $form = $this->createFormBuilder()
		 ->add('title', 'text',array('label'=>'件名'))
		 ->add('email', 'email',array('label'=>'メールアドレス'))
		 ->add('content', 'textarea',array('label'=>'お問い合わせ内容'))
		 ->add('do', 'submit', array('label'=>'登録'))
		 ->getForm();
	$form->bind($request);
	$inquiryData = $form->getData();

		if ( $form->get('do')->isClicked() && $form->isValid()){
			
			$message = \Swift_Message::newInstance()
	        ->setSubject('【まちがいさがし放題】お客様からのお問い合わせ')
	        ->setFrom($inquiryData['email'])
	        ->setTo('support@machigai.puzzle-m.net')
	        ->setBody("件名:".$inquiryData['title']."\n"."メールアドレス:".$inquiryData['email']."\n"."本文:".$inquiryData['content']);
	        
	        $this->get('mailer')->send($message);

			return $this->redirect($this->generateUrl('HelpInquiryFormComplete'));
		}
    }
    public function inquiryFormCompleteAction(){
		return $this->render('MachigaiGameBundle:Help:inquiryFormComplete.html.twig');
    }
    public function inquiryAction()
    {
		return $this->render('MachigaiGameBundle:Help:inquiry.html.twig');
    }
    public function privacyPolicyAction(){
		return $this->render('MachigaiGameBundle:Help:privacyPolicy.html.twig');
    }

}
