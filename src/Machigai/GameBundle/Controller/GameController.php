<?php

namespace Machigai\GameBundle\Controller;

use \DateTime;

use Machigai\GameBundle\Controller\BaseController;
use Machigai\GameBundle\Entity\Log;
use Machigai\GameBundle\Entity\Ranking;
use Machigai\GameBundle\Entity\PlayHistory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class GameController extends BaseController
{
    public function indexAction()
    {   
        $request = $this->get('request');
        $cookies = $request->cookies;
        $smartContract = $request->cookies->get('smartContract'); 
        if(!$this->DEBUG && (empty($smartContract) || $smartContract != "true") ) return $this->redirect($this->generateUrl('response_token'));

        return $this->render('MachigaiGameBundle:Game:index.html.twig');   
    }
    public function selectAction()
    {
        $request = $this->get('request');
        $cookies = $request->cookies;
        $smartContract = $request->cookies->get('smartContract'); 
        if(!$this->DEBUG && (empty($smartContract) || $smartContract != "true") ) return $this->redirect($this->generateUrl('response_token'));

        $user = $this->getUser();
        //historiesは未使用
        $histories = null;
/*        $questions = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                    left join  q.playHistories p 
                                    order by p.gameStatus desc, q.questionNumber asc')
                ->getResult();
*/
		$now = date("Y-m-d", strtotime("now"));
		
        $questions = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                    left join  q.playHistories p 
									where
									q.distributedFrom <= :now and
									q.distributedTo >= :now 
                                    order by q.questionNumber asc')
				->setParameter("now", $now)
                ->getResult();


        if(!empty($user)){
            $pre_playedQuestions = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:PlayHistory')
            ->findBy(array('user'=>$user->getId()));
            $playedQuestions = array();
         
            foreach ($pre_playedQuestions as $pre_questions) {
				if($pre_questions->getIsSavedGame()){
	                $playedQuestions[$pre_questions->getQuestion()->getId()] = 5;
				} else {
					$playedQuestions[$pre_questions->getQuestion()->getId()] = $pre_questions->getGameStatus();
				}
            };
        }else{
            $playedQuestions = null;
        }
        $level = "easy";
    	return $this->render('MachigaiGameBundle:Game:select.html.twig',array('playedQuestions'=>$playedQuestions,'user'=>$user,'questions'=>$questions, 'histories'=>$histories, 'sort'=> 'null', 'level' => $level));
    }
    public function sortQuestionsAction($sort, $level){
        $user = $this->getUser();
        if($user!=null){$userId = $user->getId();};
  
        $questions = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Question')
        ->findAll();
  
        switch ($sort) {
            case 'DESC':
                $questions = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:Question')
                ->findBy(array(),array('distributedFrom'=>'desc', 'id'=>'desc'));
                break;
            case 'ASC' :
                $questions = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:Question')
                ->findBy(array(),array('distributedFrom'=>'asc', 'id'=> 'asc'));
                break;
            case 'suspended':
                if($user==null){
                    $questions = array();
                    break;
                }else{
                    $pre_histories = $this->getDoctrine()
                    ->getRepository('MachigaiGameBundle:PlayHistory')
                    ->getSuspended($userId);

                    $questions = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                        left join  q.playHistories p 
                                        left join p.user u 
                                        where u.id = :id and p.isSavedGame = 1
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

                    $questions = $this->getDoctrine()
                    ->getEntityManager()
                    ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                        where q.id not in (
                                            select q1.id from MachigaiGameBundle:Question q1
                                            left join  q1.playHistories p 
                                            left join p.user u 
                                            where  u.id = :id and ( p.gameStatus = 3 or p.gameStatus = 4 )
                                        )
                                        order by q.id asc')
                    ->setParameter('id', $user->getId())
                    ->getResult();
                    break;
                }
                default:
                    break;
        }
        $playedQuestions = null;
        if(!empty($user)){
            $pre_playedQuestions = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:PlayHistory')
            ->findBy(array('user'=>$user->getId()));
            $playedQuestions = array();
         
            foreach ($pre_playedQuestions as $pre_questions) {
                if($pre_questions->getIsSavedGame()){
                    $playedQuestions[$pre_questions->getQuestion()->getId()] = 5;
                } else {
                    $playedQuestions[$pre_questions->getQuestion()->getId()] = $pre_questions->getGameStatus();
                }
            };
        }
        //historiesは未使用
        $histories = null;

        return $this->render('MachigaiGameBundle:Game:select.html.twig',array('playedQuestions'=>$playedQuestions,'user'=>$user,'questions'=>$questions,'histories'=>$histories, 'sort'=>$sort, 'level' => $level));
    }
    //makelistは未使用    
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

		$em = $this->getDoctrine()->getManager();
		
		$users = array();
		foreach( $rankings as $ranking ){
			$users[]= $ranking->getUser();
		}
		
		// すでにランクインしている場合
		if(in_array($user,$users)){

			foreach($rankings as $ranking){
				
				if($ranking->getUser() == $user &&
						$ranking->getClearTime() > $clearTime){
					
					$ranking->setClearTime($clearTime);
				}
			}
		} else {
			
			$newRank = new Ranking();
			$newRank->setUser($user->getId());
			$newRank->setYear($year);
			$newRank->setMonth($month);
			$newRank->setLevel($gameLevel);
			$newRank->setBonusPoint($bonusPoint);
			$newRank->setUpdatedAt(date("Y-m-d H:i:s"));
			$newRank->setClearTime($clearTime);
			
			$rankings[] = $newRank;
		}
		
		uksort($rankings, $this->rankingSort);	
		
		if(count($rankings) > 10){
			
			$ranking = $rankings[count($rankings) - 1];
			
			if($ranking->getId() != null){
				$em->remove($ranking);
			}
		}
		
		$rank = 1;
		foreach($rankings as $ranking){
			
			$ranking->setRank($rank);
			$em->persist($ranking);                    
			$em->flush();
			$rank += 1;
		}
    }
	function rankingSort($a, $b)
	{
		return $a->getClearTime() <> $b->getClearTime();
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
		// ポイント取得のログ追加
		if($clearPoint != 0){
			
			$log = new Log();
			$log->setUserId($user->getId());
			$log->setType("point");
			$log->setName("game_clear_get_point: " .$clearPoint);
			$log->setCreatedAt(date("Y-m-d H:i:s"));
			$em->persist($log);
			$em->flush();

			$currentPoint = $currentPoint+$clearPoint;

			$user->setCurrentPoint($currentPoint);

			$em->persist($user);
			$em->flush();
		}

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
			$playHistory = new PlayHistory();
			$playHistory->setCreatedAt(date("Y-m-d H:i:s"));
			$playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
			$playHistory->setPlayInfo($playInfo);
			$playHistory->setUser($user);
			$playHistory->setQuestion($question[0]);
			$playHistory->setGameStatus(3);
			$playHistory->setClearTime($clearTime);
			$em = $this->getDoctrine()->getManager();
			$em->persist($playHistory);
			$em->flush();
            $this->applyRanking($playHistory);
        }elseif($histories[0]->getGameStatus()!=3 and $histories[0]->getGameStatus()!=4){
            //未クリアの場合→ステータスは２回目以降のクリアへ
            foreach ($histories as $history) {
                $em = $this->getDoctrine()->getEntityManager();
                $previousData = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($history->getId());
                $em->remove($previousData);
                $em->flush();
            }

			$playHistory = new PlayHistory();
			$playHistory->setCreatedAt(date("Y-m-d H:i:s"));
			$playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
			$playHistory->setPlayInfo($playInfo);
			$playHistory->setUser($user);
			$playHistory->setQuestion($question[0]);
			$playHistory->setGameStatus(4);
			$playHistory->setClearTime($clearTime);
			$em = $this->getDoctrine()->getManager();
			$em->persist($playHistory);
			$em->flush();
        }else{
            //すでにクリア済み（ステータスが３か４）の場合、→ステータスは変更しない
            $em = $this->getDoctrine()->getEntityManager();
            $playHistory = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($histories[0]->getId());
            $playHistory->setPlayInfo($playInfo);
            $playHistory->setIsSavedGame(false);
            $playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
            $em->persist($playHistory);
            $em->flush();            
        }
        //TODO: ランキング対象は初回クリア(status=3)の場合のみ//
        /*
            if($playHistory->getGameStatus()){
                $this->applyRanking($playHistory);
            }
        */

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
			$playHistory = new PlayHistory();
			$playHistory->setCreatedAt(date("Y-m-d H:i:s"));
			$playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
			$playHistory->setPlayInfo($playInfo);
			$playHistory->setUser($user);
			$playHistory->setQuestion($question[0]);
			$playHistory->setGameStatus(2);
			$em = $this->getDoctrine()->getManager();
			$em->persist($playHistory);
			$em->flush();
        }elseif($histories[0]->getGameStatus()!=3 and $histories[0]->getGameStatus()!=4){
            //未クリアの場合→ステータスは２回目以降のプレイ           
            foreach ($histories as $history) {
                $em = $this->getDoctrine()->getEntityManager();
                $previousData = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($history->getId());
                $em->remove($previousData);
                $em->flush();
            }

			$playHistory = new PlayHistory();
			$playHistory->setCreatedAt(date("Y-m-d H:i:s"));
			$playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
			$playHistory->setPlayInfo($playInfo);
			$playHistory->setUser($user);
			$playHistory->setQuestion($question[0]);
			$playHistory->setGameStatus(2);
			$em = $this->getDoctrine()->getManager();
			$em->persist($playHistory);
			$em->flush();
        }else{
            //ステータスが３か４の場合、ステータスに変更なし
            $em = $this->getDoctrine()->getManager();
            $playHistory = $em->getRepository('MachigaiGameBundle:PlayHistory')->find($histories[0]->getId());
            $playHistory->setPlayInfo($playInfo);
            $playHistory->setIsSavedGame(false);
            $playHistory->setUpdatedAt(date("Y-m-d H:i:s"));
            $em->persist($playHistory);
            $em->flush();
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