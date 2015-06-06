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

		$base_url = "";

        $logger = $this->get('logger');
        $logger->info('inf auIdAction');
        $user = $this->getUser();
        $news = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:News')
        ->findBy(array(),array('startedAt'=>'DESC'));

        if(!empty($user)){
        
            $openId = $user->getAuId();
            
            if(!empty($openId)){

                // リワード
                $cid = "6250";
                $ad  = "install";
                $uid = hash('sha256', $openId);
                $key = "8ccc6ee910d93df31a1e48b542724e5b";

                $to_digest = "$ad:$cid:$uid:$key";
                $digest = hash('sha256', $to_digest);

                return $this->render('MachigaiGameBundle:Default:index.html.twig',
                    array(
                        'user' => $user,
                        'news'=>$news,
                        'base_url' => $base_url,
                        'cid'=>$cid,
                        'ad' => $ad,
                        'uid' => $uid,
                        'digest' => $digest,
                        'reword' => true
                    ));                
            }
        }

        return $this->render('MachigaiGameBundle:Default:index.html.twig',
            array(
                'user' => $user,
                'news'=>$news,
                'base_url' => $base_url,
                'reword' => false
            ));
    }
    public function logoutAction(Request $request){
        $session = $request->getSession();

        $id = $session->get('id');
        if(!empty($id)){
            //クッキー削除
            $response = new Response();
            $response->headers->clearCookie("myCookie");
            $response->send();

            $em = $this->getDoctrine()->getManager();
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
	
	public function puzzlelpAction()
	{
    $request = $this->get('request');
    $cookies = $request->cookies;
    $smartContract = $cookies->get('smartContract'); 
    if(!$this->DEBUG && (empty($smartContract) || $smartContract != "true") ){
      
      $session = $request->getSession();
      $session->set('puzzlelp_access', 'true');
      return $this->redirect($this->generateUrl('response_token'));
    }
    
		return $this->render('MachigaiGameBundle:Default:puzzlelp.html.twig');
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
	
	public function getBannerAction()
	{
		$base_url = "https://machigai.puzzle-m.net";
        return $this->render('MachigaiGameBundle:Default:banner.html.twig',
				array('base_url' => $base_url));
	}
	
}
