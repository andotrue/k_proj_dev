<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Machigai\GameBundle\Entity\User;
use Machigai\GameBundle\Entity\Question;
use Machigai\GameBundle\Entity\PlayHistory;
use \DateTime;

class AndroidController extends BaseController
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
	public function gameAction($id, $uid){
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

//        $user = $this->getUser();
       $user = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:User')
        ->find($uid);
//        var_dump($user);
//        $token = $request->headers->get('X-CSRF-Token');
 //       var_dump($token);

        $questionArray = array(
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
        
        $userData = null;        
        //登録ユーザの場合
        if (!empty($user)){
            $userData = array(
                'userId' => $uid,
                'currentPoint' => $user->getCurrentPoint()
            );


            $playHistoryDB = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:PlayHistory')
            ->findBy(array('user' => $user , 'question'=> $question ));
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
        if (!empty($user) && empty($playHistory)){
            $userData['isFirstTime'] = true;
        }else{
            $userData['isFirstTime'] = false;            
        }

        $playInfo = array('error'=> false, 'user' => $userData, 'question' => $questionArray, 'playHistory' => $playHistory);
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

		$request = $this->get('request');

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

	public function getUserAction(){
		$request = $this->get('request');
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
    public function gameFileAction($level,$qcode, $type){

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
    }

	public function hasValidCommonTokenAction(){
		$request = $this->get('request');
		$request_token = $request->query->get('token');
		$common_token = $this->getCommonAccessToken();
		return ($common_token == $request_token);
	}

	public function getErrorJsonResponseAction($text){
		$json = json_encode(array('error'=> $text));
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

    public function getQuestionDataAction(){
        $questions = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:Question')
            ->findAll();
        $questionData = array();
        for ($i = 0; $i < count($questions); $i++) {
/*            $playHistory = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:PlayHistory')
                ->findBy(array('user' => $user , 'question'=> $questions[$i] ));
*/
            $questionData['question'][$i]['id'] = $questions[$i]->getId();
            $questionData['question'][$i]['qcode'] = $questions[$i]->getQcode();
            $questionData['question'][$i]['level'] = $questions[$i]->getLevel();
            $questionData['question'][$i]['machigaiLimit'] = $questions[$i]->getFailLimit();
            $questionData['question'][$i]['clearPoint'] = $questions[$i]->getClearPoint();
            $questionData['question'][$i]['timeLimit'] = $questions[$i]->getTimeLimit();
//            $questionData['question'][$i]['playInfoData'] = $playHistory->getPlayInfo(); //TODO: ユーザトークンに対応
//            $questionData['question'][$i]['status'] = $playHistory->getGameStatus(); //TOOD:　ユーザトークンに対応
            $questionData['question'][$i]['status'] = "1";
            $questionData['question'][$i]['is_delete'] = false;
        }
/*
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
*/
        $questionData=json_encode($questionData);//jscon encode the array
        return new Response($questionData,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
    }
    public function uploadDataAction(){
        $logger = $this->get('logger');

        $request = $this->get('request');

        $data=$request->request->get('playInfo');
        $userToken = $request->request->get('userToken');
        $userId = 167; 
        $questionId = (int)($request->request->get('questionId'));
        //TODO: userTokenからuserを取得する実装が必要。
//        $user = $this->getUser();
//        $userId = $user->getId();

        $users = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:User')->findBy(array('syncToken' =>$userToken));
        $user = $users[0];
        $question = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:Question')->find($questionId);
        $questionId = $question->getId();

        $playHistories = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$user,'question'=>$question))
                ->getResult();
       

        if(empty($playHistories)){
            $logger->info("uploadDataAction: playHistory is null.");
            $playHistory = new PlayHistory();
//            $playHistory->setCreatedAt(new DateTime());
//            $playHistory->setUpdatedAt();
            $playHistory->setGameStatus(2); //TODO: ゲームステータス状態をきちんと取得
            $playHistory->addQuestion($question);
            $playHistory->setPlayInfo($data);
            $playHistory->setUser($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($playHistory);
            $this->applyRanking($playHistory);
            $em->flush();
            $logger->info("uploadDataAction: playHistory is saved.");
        }else{
            $playHistory = $playHistories[0];
            $logger->info("uploadDataAction: playHistory exists.");
            $playHistory->setUpdatedAt();
            $playHistory->setGameStatus(2); //TODO: ゲームステータス状態をきちんと取得
            $playHistory->setPlayInfo($data);

            $em = $this->getDoctrine()->getManager();
            $playHistory->setPlayInfo($data);
            $em->persist($playHistory);
            $this->applyRanking($playHistory);
            $em->flush();
            $logger->info("uploadDataAction: playHistory is saved.");
        }

        $responseData=json_encode(array("status" => "OK"));//json encode the array
        $logger->info("downloadAction: all done.");
        return new Response($responseData,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type

    /* 参考
    http://symfony2forum.org/threads/5-Using-Symfony2-jQuery-and-Ajax
    */
    }
    /*
    *
    *   Rankingに登録処理を行う
    */
     public function applyRanking($playHistory){
        //TODO: Ranking登録処理。
     }
}