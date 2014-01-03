<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{
    public function indexAction()
    {
        return $this->render('MachigaiGameBundle:Setting:index.html.twig');
    }

    public function nicknameAction()
    {
	$form = $this->createFormBuilder()
	 ->setMethod('GET')
 	 ->add('nickname', 'text', array('label'=>' '))
	 ->add('confirm', 'submit', array('label'=>'登録内容確認'))
	 ->add('state','hidden')
	 ->getForm();
        return $this->render('MachigaiGameBundle:Setting:nickname.html.twig', array('form' => $form->createView()) );
    }

    public function completeAction()
    {
        return $this->render('MachigaiGameBundle:Setting:complete.html.twig');
    }

}
