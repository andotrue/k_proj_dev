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
        $session = $request->getSession();

        //開発モード時,セッションを生成する。
        $MODE = 'DEV';
        $user_type = 'loggedIn';


        if( $MODE == 'DEV'){
            if($user_type == 'loggedIn'){
                //ログインユーザの場合
                $session->set('auId', 'auid1');
                $session->set('id', '1');
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

        $em = $this->getDoctrine()->getEntityManager();
//        $user = $em->getRepository('MachigaiGameBundle:User')->findByAuId($auId)[0];
        $user = $em
            ->createQuery('SELECT u FROM MachigaiGameBundle:User u ORDER BY u.auId ASC')  
            ->getResult();  
        $user->setNickName($nickName);
        $em->flush();
        return $this->render('MachigaiGameBundle:Register:complete.html.twig');
    }
    public function confirmAction(){
        return $this->render('MachigaiGameBundle:Register:confirm.html.twig');
    }

}
