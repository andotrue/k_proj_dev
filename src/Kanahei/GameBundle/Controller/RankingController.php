<?php

namespace Kanahei\GameBundle\Controller;

use Kanahei\GameBundle\Controller\BaseController;
use Kanahei\GameBundle\Entity\Log;
use Symfony\Component\HttpFoundation\Response;

class RankingController extends BaseController
{
    public function indexAction()
    {
    $month = date('n');
    $year = date('Y');
    if($month == 1){
      $pre_year = $year-1;
      $pre_month = 12;
    }else{
      $pre_year = $year;
      $pre_month = $month-1;
    }
    $ranking_this_month = $this->getDoctrine()
        ->getRepository('KanaheiGameBundle:Ranking')
        ->findBy(array('month'=>$month,'year'=>$year),array('rank'=>'ASC'));
    $ranking_previous_month = $this->getDoctrine()
        ->getRepository('KanaheiGameBundle:Ranking')
        ->findBy(array('month'=>$pre_month,'year'=>$pre_year),array('rank'=>'ASC'));
        
	return $this->render('KanaheiGameBundle:Ranking:index.html.twig',array('ranking_this_month' => $ranking_this_month, 'ranking_previous_month' => $ranking_previous_month));
    }

	// 月初に実行されることを想定
	public function summaryAction(){

		$em = $this->getDoctrine()->getManager();
		//　トランザクション開始
		$em->getConnection()->beginTransaction();
		
		try {
			$month = date('n');
			$year = date('Y');
			if($month == 1){
			  $pre_year = $year-1;
			  $pre_month = 12;
			}else{
			  $pre_year = $year;
			  $pre_month = $month-1;
			}

 			// 先月のランキングを取得
			$ranking_previous_month = $this->getDoctrine()
				 ->getRepository('KanaheiGameBundle:Ranking')
				 ->findBy(
						 array(
							'month'=>$pre_month,
							'year'=>$pre_year,
							'bonusPoint'=>null),
						 array(
							'rank'=>'ASC'),
						 30);
			
			// 取得者にポイントを加算
			foreach($ranking_previous_month as $data){
				
				$rps = $this->getDoctrine()
				 ->getRepository('KanaheiGameBundle:RankingPoint')
				 ->findBy(
						 array(
							'level'=>$data->getLevel(),
							'rank'=>$data->getRank()
						 )
				);
				$rp = $rps[0];
				$bonus_point = $rp->getBonusPoint();
				
				$data->setBonusPoint($bonus_point);
				$user = $data->getUser();
				
				$log = new Log();
				$log->setUserId($user->getId());
				$log->setType("point");
				$log->setName("ranking_get_point: " .$bonus_point);
				$log->setCreatedAt(date("Y-m-d H:i:s"));
				$em->persist($log);
				$em->flush();
				
				$user->setCurrentPoint($user->getCurrentPoint() + $bonus_point);
				
				$em->persist($data);
				$em->persist($user);
			}
		
			// トランザクション終了
			$em->getConnection()->commit();
			$em->flush();
		} catch (ErrorException $ex){
			$em->getConnection()->rollback();
		}
		
		$em->close();
		return new Response('ok');
	}
}