<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    public function indexAction(Request $request)
    {	
	$form = $this->createFormBuilder()
	 ->setMethod('GET')
 	 ->add('nickname', 'text')
     ->add('auId', 'hidden')
	 ->add('confirm', 'submit', array('label'=>'内容を確認'))
	 ->getForm();
     
     if ($request->getMethod() == 'POST') {
        $form->bind($request);
        if ($form->isValid()) {
            // データベースへの保存など、何らかのアクションを実行する
        }
    }
        return $this->render('MachigaiGameBundle:Register:index.html.twig', array('form' => $form->createView()) );
    }

    public function completeAction()
    { 
/*        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MachigaiGameBundle:User')->findByAuId($auId)[0];
        $user->setNickName($nickName);
        $em->flush();
*/        return $this->render('MachigaiGameBundle:Register:complete.html.twig');
    }
    public function confirmAction(){
        return $this->render('MachigaiGameBundle:Register:confirm.html.twig');
    }

}
