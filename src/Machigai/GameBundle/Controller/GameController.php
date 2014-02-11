<?php

namespace Machigai\GameBundle\Controller;

use \DateTime;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Controller\BaseController;
use Machigai\GameBundle\Entity\Ranking;
use Machigai\GameBundle\Entity\PlayHistory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GameController extends BaseController
{
    public function indexAction()
    {   
        return $this->render('MachigaiGameBundle:Game:index.html.twig');   
    }
    public function selectAction()
    {
        $user = $this->getUser();
        $histories = null;
/*        $questions = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                    left join  q.playHistories p 
                                    order by p.gameStatus desc, q.questionNumber asc')
                ->getResult();
*/
         $questions = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                    left join  q.playHistories p 
                                    order by q.questionNumber asc')
                ->getResult();


        if(!empty($user)){
            $pre_playedQuestions = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:PlayHistory')
            ->findBy(array('user'=>$user->getId()));
            $playedQuestions = array();
         
            foreach ($pre_playedQuestions as $pre_questions) {
				if($pre_questions->getIsSavedGame()){
	                $playedQuestions[$pre_questions->getQuestion()->getId()] = 99;
				} else {
					$playedQuestions[$pre_questions->getQuestion()->getId()] = $pre_questions->getGameStatus();
				}
            };
        }else{
            $playedQuestions = null;
        }
    	return $this->render('MachigaiGameBundle:Game:select.html.twig',array('playedQuestions'=>$playedQuestions,'user'=>$user,'questions'=>$questions,'histories'=>$histories));
    }
    public function sortQuestionsAction($sort){
        $user = $this->getUser();
        if($user!=null){$userId = $user->getId();};
        $histories = null;
  
        $questions = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Question')
        ->findAll();
  
        switch ($sort) {
            case 'DESC':
            case 'ASC' :
                $questions = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:Question')
                ->findBy(array(),array('createdAt'=>$sort));
                break;
            case 'suspended':
                if($user==null){
                    $histories = array();
                    break;
                }else{
                    $pre_histories = $this->getDoctrine()
                    ->getRepository('MachigaiGameBundle:PlayHistory')
                    ->getSuspended($userId);

                    $histories = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p 
                                        left join  p.question q 
                                        left join p.user u 
                                        where u.id = :id and p.gameStatus = 3 
                                        order by q.id asc')
                    ->setParameter('id', $user->getId())
                    ->getResult();
                    break;
                }
            case 'notCleared':
                if($user==null){
                    $histories = array();
                    break;
                }else{
                    $pre_histories = $this->getDoctrine()
                    ->getRepository('MachigaiGameBundle:PlayHistory')
                    ->getNotCleared($userId);

                    $histories = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p 
                                        left join  p.question q 
                                        left join p.user u 
                                        where u.id = :id and p.gameStatus = 2 
                                        order by q.id asc')
                    ->setParameter('id', $user->getId())
                    ->getResult();
                    break;
                }
                default:
                    break;
        }
        $list = $this->makeList($histories);
        $playedQuestions = null;
        return $this->render('MachigaiGameBundle:Game:select.html.twig',array('playedQuestions'=>$playedQuestions,'user'=>$user,'questions'=>$questions,'histories'=>$histories));
    }
    public function makeList($histories){
        $list = array();
        for ($i=0; $i < count($histories); $i++) { 
            $history = $histories[$i];
            $question = $history->getQuestion();
            $data = array(  'gameStatus' => $history->getGameStatus(),
                            'questionId' => $question->getId()
                        );
            $list[$i] = $data;
       }
        return $list;
    }
    public function getPlayHistory()
    {
        $history = array('1' => array( '1' => array( 'qcode'=>'1' )));
        return json_encode($history);
    }

    public function playAction($id)
    {
        $user = $this->getUser();
        $uid = 0;
        if(!empty($user)){
            $uid = $user->getId();
        }
        $token = $this->get('form.csrf_provider')->generateCsrfToken('csrf_token');
    	return $this->render('MachigaiGameBundle:Game:index.html.twig', array('csrf_token' => $token, 'uid' => $uid));	
    }
    public function downloadByJSONAction($id)
    {

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
                'suspendedTime' => $playHistoryDB[0]->getSuspendTime(),
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

    public function downloadAction($level,$qcode,$type)
    {
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
        return  $response->send();
    }

    public function finishAction()
    {
	return $this->render('MachigaiGameBundle:Game:finish.html.twig');	
    }

    public function clearAction()
    {
	return $this->render('MachigaiGameBundle:Game:clear.html.twig');	
    }

    public function failAction()
    {
	return $this->render('MachigaiGameBundle:Game:fail.html.twig');	
    }
    public function uploadDataAction(){
        $request = $this->get('request');
        /* get data like below from view

        {
            'playInfo':playInfo text,
            'questionId':question_id
        }

        */
        $data=$request->request->get('playInfo');
        $questionId = $request->request->get('questionId');
        $user = $this->getUser();
        $userId = $user->getId();
        $playHistory = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$userId,'question'=>$questionId))
                ->getResult();
        if(empty($playHistory)){
            $em = $this->getDoctrine()->getEntityManager();
            $playHistory = $em->getRepository('MachigaiGameBundle:PlayHistory')->findBy(array('userId'=>$userId));
            $playHistory->setPlayInfo($data);
            $em->flush();
        }else{
            $playHistoryId = $playHistory->getId();
            $em = $this->getDoctrine()->getEntityManager();
            $playHistory = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($playHistoryId);
            $playHistory->setPlayInfo($data);
            $em->flush();
        }
    /* 参考
    http://symfony2forum.org/threads/5-Using-Symfony2-jQuery-and-Ajax
    */
    }
    public function downloadDataAction(){
        $request = $this->get('request');
        $questionId=$request->query->get('questionId');

        $user = $this->getUser();
        $userId = $user->getId();

        $playHistory = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$userId,'question'=>$questionId))
                ->getResult();

        $playInfo = $playHistory[0]->getPlayInfo();
        $playInfo=json_encode($playInfo);//jscon encode the array
        return new Response($playInfo,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
    }
    public function rankingRegisterAction(){
        $request = $this->get('request');
        /* get data like below from view

        {
            'clearTime':clearTime,
            'questionId':question_id,
            'gameLevel':gameLevel,
            'bonusPoint':bonusPoint
        }

        */
        $clearTime = $request->request->get('clearTime');
        $questionId = $request->request->get('questionId');
        $gameLevel = $request->request->get('gameLevel');
        $bonusPoint = $request->request->get('bonusPoint');
        $user = $this->getUser();
        $userId = $user->getId();
        $month = date('n');
        $year = date('Y');
        $rankings = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT r from MachigaiGameBundle:Ranking r
                                    where r.level = :gameLevel and r.year = :year and r.month = :month order by r.rank asc')
                ->setParameters(array('gameLevel'=>$gameLevel,'year'=>$year,'month'=>$month))
                ->getResult();
	/** ランキング初登録 **/
	if(empty($rankings)){
	/**
		$em = $this->getDoctrine()->getManager();
		$newRank = new Ranking();
		$newRank->setUser($userId);
                $newRank->setYear($year);
                $newRank->setMonth($month);
                $newRank->setLevel($gameLevel);
		$newRank->setRank(1);
                $newRank->setBonusPoint($bonusPoint);
                $newRank->setUpdatedAt(date("Y-m-d H:i:s"));
		$em->persist($newRank);                    
		$em->flush();                    
                break;
	**/
	}
        elseif($clearTime >= $rankings[9]->getClearTime()){
            return $this->render('MachigaiGameBundle:Game:');//
        }else{
            $playHistory = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$userId,'question'=>$questionId))
                ->getResult();

            foreach ($rankings as $rank) {
                if($clearTime < $rank->getClearTime()){
                    $rankId = $rank->getId();
                    $em = $this->getDoctrine()->getEntityManager();
                    $newRank = $em->getRepository('MachigaiGameBundle:Ranking')->findBy(array('id'=>$rankId));
                    $newRank->setUser($userId);
                    $newRank->setYear($year);
                    $newRank->setMonth($month);
                    $newRank->setLevel($gameLevel);
                    $newRank->setRank($rank->getRank());
                    $newRank->setBonusPoint($bonusPoint);
                    $newRank->setUpdatedAt(date("Y-m-d H:i:s"));
                    $em->persist($newRank);                    
                    $em->flush();                    
                    break;
                }
            }
        }
    }
    public function resultUserClearAction(){
        $user = $this->getUser();
        $userId = $user->getId();
        $pre_currentPoint = $user->getCurrentPoint();

        $request = $this->get('request');
        $clearTime = $request->query->get('clearTime');
        $questionId = $request->query->get('questionId');
        $playInfo = $request->query->get('playInfo');
    
        $question = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q
                                    where q.id = :id')
                ->setParameters(array('id'=>$questionId))
                ->getResult();    


		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager();

        $histories = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:PlayHistory')->findBy(array('user'=>$user,'question'=>$question[0]));

		$currentPoint = $pre_currentPoint;
		$clearPoint = 0;
		
		// 初回クリア
		if(empty($histories)){
	        $clearPoint = 
				$question[0]->getBonusPoint() + $question[0]->getClearPoint();;
		}
		if(!empty($histories)){
			$history = $histories[0];
			$status = $history->getGameStatus();
			if($status != 3 && $status != 4){
				$clearPoint = $clearPoint +  $question[0]->getClearPoint();
			}
		}
		$currentPoint = $currentPoint+$clearPoint;

		$user->setCurrentPoint($currentPoint);
		
		$em->persist($user);
		$em->flush();

        //TODO: クリアタイム計算
        $duration = 0;


        $data = json_decode($playInfo, true);
        $clockData = $data["clockData"];

        foreach($clockData as $datum){
            //AndroidとWebAppでは時刻計算手法が違う。
            //Android: 整数（long型）
            //WebApp:  時刻形式
            $interrupted = null;
            $resumed = null;
            $interrupted  = (int)$datum['interrupted'];
            $resumed =  (int)$datum['resumed'];
            $duration += $interrupted - $resumed;
        }


        if(empty($histories)){
            for($i = 0;$i<count($playInfo); $i++){
                $playHistory = new PlayHistory();
                $playHistory->setCreatedAt(date("Y-m-d H:i:s"));
                $playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
                $playHistory->setPlayInfo($playInfo[$i]);
                $playHistory->setUser($user);
                $playHistory->setQuestion($question[0]);
                $playHistory->setGameStatus(3);
                $playHistory->setClearTime($duration);
                $em = $this->getDoctrine()->getManager();
                $em->persist($playHistory);
                $em->flush();
            }
        }else{
            foreach ($histories as $history) {
                $em = $this->getDoctrine()->getEntityManager();
                $previousData = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($history->getId());
                $em->remove($previousData);
                $em->flush();
            }

            for($i = 0;$i<count($playInfo); $i++){
                $playHistory = new PlayHistory();
                $playHistory->setCreatedAt(date("Y-m-d H:i:s"));
                $playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
                $playHistory->setPlayInfo($playInfo[$i]);
                $playHistory->setUser($user);
                $playHistory->setQuestion($question[0]);
                $playHistory->setGameStatus(4);
                $playHistory->setClearTime($duration);
                $em = $this->getDoctrine()->getManager();
                $em->persist($playHistory);
                $em->flush();
            }
        }
        //TODO: ランキング対象は初回クリア(status=3)の場合のみ//
        /*
            if($playHistory->getGameStatus()){
                $this->applyRanking($playHistory);
            }
        */
        $this->applyRanking($playHistory);

        return $this->render('MachigaiGameBundle:Game:resultUserClear.html.twig',array('clearTime'=>$clearTime,'clearPoint'=>$clearPoint,'currentPoint'=>$currentPoint));
    }
    public function resultGuestClearAction(){       
        $request = $this->get('request');
        $clearTime = $request->query->get('clearTime');

        return $this->render('MachigaiGameBundle:Game:resultGuestClear.html.twig',array('clearTime'=>$clearTime));
    }
    public function resultUserFalseAction(){
        $user = $this->getUser();
        $userId = $user->getId();
        $currentPoint = $user->getCurrentPoint();

/*        $request = $this->get('request');
        $questionId = $request->query->get('questionId');
    
        $question = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q
                                    where q.id = :id')
                ->setParameters(array('id'=>$questionId))
                ->getResult();
*/

        $request = $this->get('request');
        $questionId = $request->query->get('questionId');
        $playInfo = $request->query->get('playInfo');
    
        $question = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q
                                    where q.id = :id')
                ->setParameters(array('id'=>$questionId))
                ->getResult();    

        $clearPoint = $question[0]->getClearPoint();

        $histories = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:PlayHistory')->findBy(array('user'=>$user,'question'=>$question[0]));

        if(empty($histories)){
            for($i = 0;$i<count($playInfo); $i++){
                $playHistory = new PlayHistory();
                $playHistory->setCreatedAt(date("Y-m-d H:i:s"));
                $playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
                $playHistory->setPlayInfo($playInfo[$i]);
                $playHistory->setUser($user);
                $playHistory->setQuestion($question[0]);
                $playHistory->setGameStatus(3);
                $em = $this->getDoctrine()->getManager();
                $em->persist($playHistory);
                $em->flush();
            }
        }elseif($histories[0]->getGameStatus()!=4 and $histories[0]->getGameStatus()!=5){
            foreach ($histories as $history) {
                $em = $this->getDoctrine()->getEntityManager();
                $previousData = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($history->getId());
                $em->remove($previousData);
                $em->flush();
            }

            for($i = 0;$i<count($playInfo); $i++){
                $playHistory = new PlayHistory();
                $playHistory->setCreatedAt(date("Y-m-d H:i:s"));
                $playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
                $playHistory->setPlayInfo($playInfo[$i]);
                $playHistory->setUser($user);
                $playHistory->setQuestion($question[0]);
                $playHistory->setGameStatus(4);
                $em = $this->getDoctrine()->getManager();
                $em->persist($playHistory);
                $em->flush();
            }
        }


        return $this->render('MachigaiGameBundle:Game:resultUserFalse.html.twig',array('questionId'=>$questionId,'currentPoint'=>$currentPoint));
    }
    public function resultGuestFalseAction(){
        $request = $this->get('request');
        $questionId = $request->query->get('questionId');

        return $this->render('MachigaiGameBundle:Game:resultGuestFalse.html.twig',array('questionId'=>$questionId));
    }

    public function saveGameDataAction(){

		$request = $this->get('request');

        $data = $request->get('playInfo');
        $status = $request->get("gameStatus");
        $questionId = (int)($request->get('questionId'));
        $isSavedGame = $request->get("isSavedGame");
		
		if(empty($isSavedGame) || $isSavedGame == "false"){
			$isSavedGame = false;
		} else {
			$isSavedGame = true;
		}
		
        $user = $this->getUser();

		$params = array(
			"data" => $data,
			"user" => $user,
			"status" => $status,
			"isSavedGame" => $isSavedGame,
			"questionId" => $questionId
		);
		$this->saveGameData($params);

		return $this->redirect("/game/select");
    }
}