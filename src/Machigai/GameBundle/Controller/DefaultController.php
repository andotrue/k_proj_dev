<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends BaseController
{
    public function indexAction()
    {
        //スマートパスが有効かどうか
        $request = $this->get('request');
        $cookies = $request->cookies;
        $smartContract = $request->cookies->get('smartContract'); 
        if(!$this->DEBUG && (empty($smartContract) || $smartContract != "true") ) return $this->redirect($this->generateUrl('response_token'));


        $logger = $this->get('logger');
        $logger->info('inf auIdAction');
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
            //クッキー削除
            $response = new Response();
            $response->headers->clearCookie("myCookie");
            $response->send();

            $em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository('MachigaiGameBundle:User')->find($id);
            $em->flush();

            $request = $this->get('request');
            //$cookies = $request->cookies;
			
            $session->remove('id');
//            $session->remove('auId');
            $session->remove('syncToken');

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
	
	public function lpAction()
	{
		return $this->render('MachigaiGameBundle:Default:lp.html.twig');
	}
	
	public function lpCompanyInfoAction()
	{
		return $this->render('MachigaiGameBundle:Default:companyInfo.html.twig');
	}

	public function lpPrivacyPolicyAction()
	{
		return $this->render('MachigaiGameBundle:Default:privacyPolicy.html.twig');
	}

	public function lpTermsAction()
	{
		return $this->render('MachigaiGameBundle:Default:terms.html.twig');
	}
	
}
