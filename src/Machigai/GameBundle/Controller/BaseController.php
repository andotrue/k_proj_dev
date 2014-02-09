<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Entity\PlayHistory;

class BaseController extends Controller
{
	public function getUser()
	{        
        $session = $this->get('session');
        $id = $session->get('id');
        if(!empty($id)){
        	$user = $this->getDoctrine()
	        ->getRepository('MachigaiGameBundle:User')
	        ->find($id);
			return $user;
        }else{
        	//GUESTの場合NULLを返す
            $user = NULL;
            return $user;
        }
	}
    public function getPurchasedItems(){
        $user = $this->getUser();
        $user_id = $user->getId();
        $pre_purchasedItems = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:PurchaseHistory')
        ->findByUser($user_id);
        $purchasedItems = array();
        foreach ($pre_purchasedItems as $purchasedItem) {
            $times = $purchasedItem->getCreatedAt();
            foreach ($times as $from) {
                $to = date( "Y-m-d H:i:s", time());
                $fromSec = strtotime($from);
                $toSec   = strtotime($to);
                $differences = $toSec - $fromSec;
                //30days
                if($differences < 2592000){
                    $purchasedItems[] = $purchasedItem->getItem()->getId();
                }
            }       
        }
        return $purchasedItems;
    }
	
	public function saveGameData($params){
		
        $question = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:Question')->find($params["questionId"]);

        $playHistories = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$params["user"],'question'=>$question))
                ->getResult();
       

        if(empty($playHistories)){
//            $logger->info("uploadDataAction: playHistory is null.");
            $playHistory = new PlayHistory();
//            $playHistory->setCreatedAt(new DateTime());
//            $playHistory->setUpdatedAt();
            $playHistory->addQuestion($question);
            $playHistory->setPlayInfo($params["data"]);
            $playHistory->setUser($params["user"]);
            $playHistory->setGameStatus($params["status"]);
            $playHistory->setIsSavedGame($params["isSavedGame"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($playHistory);
            $this->applyRanking($playHistory);
            $em->flush();
//            $logger->info("uploadDataAction: playHistory is saved.");
        }else{
            $playHistory = $playHistories[0];
			$updatedAt = new \DateTime();
            $playHistory->setUpdatedAt($updatedAt->format("Y-m-d H:i:s"));
            $playHistory->setGameStatus($params["status"]);
            $playHistory->setPlayInfo($params["data"]);
            $playHistory->setIsSavedGame($params["isSavedGame"]);

            $em = $this->getDoctrine()->getManager();
            $playHistory->setPlayInfo($params["data"]);
            $em->persist($playHistory);
            $this->applyRanking($playHistory);
            $em->flush();
        }		
	}
	
    /*
    *
    *   Rankingに登録処理を行う
    */
     public function applyRanking($playHistory){
        //TODO: Ranking登録処理。
     }
	
}