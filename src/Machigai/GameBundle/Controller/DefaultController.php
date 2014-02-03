<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        $user = $this->getUser();
        $news = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:News')
        ->findBy(array(),array('startedAt'=>'DESC'));

        return $this->render('MachigaiGameBundle:Default:index.html.twig', array('user' => $user,'news'=>$news));
    }
    public function logoutAction(Request $request){
        $session = $request->getSession();

        $id = $session->get('id');
        if(!empty($id)){
	        $session->remove('id');

	        //表示していないが、とりあえず
	        $this->get('session')->getFlashBag()->add(
	            'notice',
	            'ログアウトしました。'
	        );
        }
        return $this->redirect($this->generateUrl('Top'));                
	}
    public function errorAction()
    {
	return $this->render('MachigaiGameBundle:Default:error.html.twig');
    }
}
