<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

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
	public function gameAction($id){
/*		//ゲスト用トークンチェック
		if (!$this->hasValidCommonToken())
			return $response = $this->getErrorJsonResponse('Invalid User')->send();			
*/		

       $question = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Question')
        ->find($id);

        $isError = false;
        $error = null;
        if( $question == null ){
            $error = array('error' => true, 'errorType' => 'NOT_EXIST', 'message' => "問題が存在しません。");
            $isError = true;
        }else{
            if( $question->getIsDelete() == true){
                $error = array('error' => true, 'errorType' => 'DELETED', 'message' => "問題は削除されました。");
                $isError = true;
            }
            if( $question->getDistributedFrom() > new DateTime() || new DateTime() > $question->getDistributedTo() ){
                $error = array('error' => true, 'errorType' => 'NOT_EXIST', 'message' => "問題が存在しません。");
                $isError = true;
            }
        }
        if ($isError == true){
            $json = json_encode($error);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }


        $questionNumber = $question->getQuestionNumber();
        $failLimit = $question->getFailLimit();
        $timeLimit = $question->getTimeLimit();
        $clearPoint = $question->getClearPoint();
        $bonusPoint = $question->getBonusPoint();
        $level = $question->getLevel();
        $qcode = $question->getQcode();
        $questionTitle = $question->getQuestionTitle();

        $copyrightFileName = "";

        $user = $this->getUser();
        
        $question = array(
                'questionId' => $question->getId(),
                'questionNumber' => $questionNumber,
                'failLimit' => $failLimit,
                'timeLimit' => $timeLimit,
                'clearPoint' => $clearPoint,
                'bonusPoint' => $bonusPoint,
                'level' => $level,
                'qcode' => $qcode,
                'questionTitle' => $questionTitle,
            );

        //登録ユーザの場合
        if (!empty($user)){
            $playHistoryDB = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:PlayHistory')
            ->findBy(array('userId'=> $userId, 'questionId'=> $question->getId() ));
        }
        //playHistoryデータがあった場合

        
        if (!empty($playHistoryDB)){
            $playHistory = array(
                'playHistoryId' => $playHistoryDB[0]->getId(), 
                'playStartedAt' => $playHistoryDB[0]->getPlayStartedAt(), 
                'playEndedAt' => $playHistoryDB[0]->getPlayEndedAt(),
                'clearTime' => $playHistoryDB[0]->getClearTime(),
                'suspendedTime' => $playHistoryDB[0]->getSuspendedTime(),
                'gameStatus' => $playHistoryDB[0]->getGameStatus(),
                'playInfo' => $playHistoryDB[0]->getPlayInfo(), // Javascriptでは playData
                );
        }else{
            $playHistory = null;       
        }

        $playInfo = array('error'=> false, 'question' => $question, 'playHistory' => $playHistory);
        $json = json_encode($playInfo);
        $json = $json;
        $logger = $this->get('logger');
        $logger->info($json);
        $response = new Response($json);
//        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Type', 'text/javascript');
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