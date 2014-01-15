<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AndroidController extends Controller
{
	public function getCommonAccessToken(){
		return 'h6C43S5SS7wMu7JNuy3LM8E4';
	}

	public function indexAction(){
		$user = $this->getUserInfo();
		if (empty($user)){
			$json = array('error' =>'invalid user' );
			$response = new Response($json);
			$response->headers->set('Content-Type', 'application/json');
			return $response->send();
		}
		$request = $this->getRequest();
		$json = json_encode(array());
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response->send();
	}

	//ユーザ識別用トークンが必要
	public function userAction(){
		$user = $this->getUser();

		if(empty($user))
			return $this->getErrorJsonResponse('Invalid User')->send();

//		$serializer = $this->get('jms_serializer');
//		$json = $serializer->serialize($user, 'json');
		$json = $user->toJsonForSync(); 
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	//ゲスト用トークンが必要
	public function noticesAction(){
		//ゲスト用トークンチェック
		if(!$this->hasValidCommonToken()) 
			return $this->getErrorJsonResponse('Invalid User')->send();

		$news = $this->getDoctrine()
			->getRepository('MachigaiGameBundle:News')
			->findAll();
		$html = "<ul>";
		for ($i=0; $i < sizeof($news); $i++) {
			$new = $news[$i];
			$html .= '<li>'.$new->getTitle() . $new->getDescription().'</li>';
		}
		$html .='</ul>';
		$response = new Response($html);
		$response->headers->set('Content-Type', 'text/html');
		$response->headers->set('Content-Type', 'utf8');
		return $response;
	}

	//ゲスト用トークンが必要
	public function gameAction(){
		//ゲスト用トークンチェック
		if (!$this->hasValidCommonToken())
			return $response = $this->getErrorJsonResponse('Invalid User')->send();			
		

		$request = $this->getRequest();

		$json = json_encode(array(''));
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');

		return $response;
	}

	//ゲスト用トークンが必要	
	public function gameDataAction($id, $file_name){
		//ゲスト用トークンチェック
		if(!$this->hasValidCommonToken()) 
			return $this->getErrorJsonResponse('Invalid User')->send();

		$request = $this->getRequest();

       $types = array('xml' => '.xml', 'first' => '_1.png', 'second' => '_2.png');
        $format = $types[$type];
        $file = dirname(__FILE__).'/../Resources/questions/'.$level.'/'. $qcode . '/MS'. sprintf('%05d',$qcode). $format;

        $response = new BinaryFileResponse($file);
//        $response->prepare($request);
        if ($type == 'xml'){
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('charset', 'UTF-8');
        }else{
            $response->headers->set('Content-Type', 'image/png');
        }
        return  $response;

		$json = json_encode(array());
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}


	public function getUser(){
		$request = $this->getRequest();
		$sync_token = $request->query->get('token');

        if(!empty($sync_token)){
        	$users = $this->getDoctrine()
        	->getEntityManager()
			->createQuery('SELECT u FROM MachigaiGameBundle:User u where u.syncToken = :sync_token')
			->setParameter('sync_token', $sync_token)
            ->getResult();  

            return $users[0];
        }else{
        	//GUESTの場合NULLを返す
            return NULL;
        }
	}

	public function hasValidCommonToken(){
		$request = $this->getRequest();
		$request_token = $request->query->get('token');
		$common_token = $this->getCommonAccessToken();
		return ($common_token == $request_token);
	}

	public function getErrorJsonResponse($text){
		$json = json_encode(array('error'=> $text));
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

}