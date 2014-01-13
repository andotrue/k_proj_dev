<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;

class HelpController extends BaseController
{
    public function indexAction()
    {
	return $this->render('MachigaiGameBundle:Help:index.html.twig');
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
		 ->add('title', 'text',array('label'=>'件名'))
		 ->add('email', 'email',array('label'=>'メールアドレス'))
		 ->add('content', 'textarea',array('label'=>'お問い合わせ内容'))
		 ->add('confirm', 'submit', array('label'=>'入力内容確認'))
		 ->getForm();
	$form->handleRequest($request);		
	if( $form->get('confirm')->isClicked() ){
	 $data = $form->getData();
	 $form = $this->createFormBuilder()
		 ->setAction($this->generateUrl('HelpInquiryFormDo'))
		 ->setMethod('POST')
		 ->add('title', 'text',array('label'=>'件名', 'attr'=>array('disabled'=>'disabled')))
		 ->add('email', 'email',array('label'=>'メールアドレス', 'attr'=>array('disabled'=>'disabled')))
		 ->add('content', 'textarea',array('label'=>'お問い合わせ内容', 'attr'=>array('disabled'=>'disabled')))
		 ->add('do', 'submit', array('label'=>'登録'))
		 ->add('ammend','button',array('label'=>'修正', 'attr'=>array('onclick'=>'history.back()')))
		 ->getForm();
	 $form->setData($data);
         return $this->render('MachigaiGameBundle:Help:confirm.html.twig', array('form' => $form->createView()) );
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
	$form->handleRequest($request);		
	 #TODO: Deal with nickname registration.
	if ( $form->get('do')->isClicked() && $form->isValid()){
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

}
