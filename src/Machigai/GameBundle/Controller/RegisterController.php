<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegisterController extends Controller
{
    public function indexAction()
    {	
	$form = $this->createFormBuilder()
	 ->setMethod('GET')
 	 ->add('nickname', 'text')
	 ->add('confirm', 'submit', array('label'=>'内容を確認'))
	 ->add('state','hidden')
	 ->getForm();
        return $this->render('MachigaiGameBundle:Register:index.html.twig', array('form' => $form->createView()) );
	
    }

    public function completeAction()
    {
        return $this->render('MachigaiGameBundle:Register:complete.html.twig');
    }

}
