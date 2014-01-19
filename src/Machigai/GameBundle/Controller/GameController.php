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
        $questions = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT q from MachigaiGameBundle:Question q 
                                    left join  q.playHistories p 
                                    order by p.gameStatus desc, q.questionNumber asc')
                ->getResult();

        if(!empty($user)){
            $pre_playedQuestions = $this->getDoctrine()
            ->getRepository('MachigaiGameBundle:PlayHistory')
            ->findBy(array('user'=>$user->getId()));
            $playedQuestions = array();
         
            foreach ($pre_playedQuestions as $pre_questions) {
                $playedQuestions[] = $pre_questions->getQuestion()->getId();
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
    	return $this->render('MachigaiGameBundle:Game:index.html.twig');	
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
    public function rankingRegister(){
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
        $ranking = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT r from MachigaiGameBundle:Ranking r
                                    where r.level = :gameLevel and r.year = :year and r.month = :month order by r.rank asc')
                ->setParameters(array('gameLevel'=>$gameLevel,'year'=>$year,'month'=>$month))
                ->getResult();
        if($clearTime >= $ranking[9]->getClearTime()){
            return $this->render('MachigaiGameBundle:Game:');//
        }else{
            $playHistory = $this->getDoctrine()
                ->getEntityManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$userId,'question'=>$questionId))
                ->getResult();

            foreach ($ranking as $rank) {
                if($clearTime < $rank->getClearTime()){
                    $rankId = $rank->getId();
                    $em = $this->getDoctrine()->getEntityManager();
                    $newRank = $em->getRepository('MachigaiGameBundle:Ranking')->findBy(array('id'=>$rankId));
                    $newRank->setUser($userId);
                    $newRank->setPlayHistoryId($playHistory[0]->getId());
                    $newRank->setYear($year);
                    $newRank->setMonth($month);
                    $newRank->setLevel($gameLevel);
                    $newRank->setRank($rank->getRank());
                    $newRank->setBonusPoint($bonusPoint);
                    $newRank->setUpdatedAt(date("Y-m-d H:i:s"));
                    $em->flush();                    
                    break;
                }
            }
        }
    }
}
