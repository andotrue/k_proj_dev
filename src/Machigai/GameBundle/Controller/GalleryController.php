<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\PurchaseHistory;
use Machigai\GameBundle\Entity\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class GalleryController extends BaseController
{
    public function indexAction()
    {
    	$request= $this->get('request');
        $page = $request->query->get("page");
        $user = $this->getUser();
        $purchasedItems2 = $this->getPurchasedItems();
        
        $pageCount = 3;
        
        // ページ
        if(empty($page)){
            $page = 1;
        }

        $offset_base = $page -1;
        $offset = $offset_base * $pageCount;
/*
        $purchasedItems = $this->getDoctrine()
			        		->getRepository('MachigaiGameBundle:PurchaseHistory')
			        		->findBy(
        						array("user"=>$user->getId()),
        						//array("createdAt", ">", "now()"),
        						array("createdAt"=>"DESC"),
        						$pageCount,
        						$offset
        					);
       						//->andWhere('created_at > ?', 'now()');
		       						 */
		$repository = $this->getDoctrine()  
							->getRepository('MachigaiGameBundle:PurchaseHistory');  
		$query = $repository->createQueryBuilder('ph')  
								->where('ph.user = :user_id', 'ph.createdAt >= :date')
								->setParameter('user_id', $user->getId())
								->setParameter('date', date('Y-m-d', strtotime('-6 month')))
								->orderBy('ph.createdAt', 'DESC')
								->setMaxResults($pageCount)
								->setFirstResult($offset)
								->getQuery(); 
		$purchasedItems = $query->getResult(); 
        
        //全件数取得
        $count = count($purchasedItems2);
        $maxPage = ceil($count / $pageCount);
        
        return $this->render(
				'MachigaiGameBundle:Gallery:index.html.twig',
				array(
						'user'=>$user,
						'purchasedItems'=>$purchasedItems,
						'purchasedItems2'=>$purchasedItems2,
						'page' => $page,
						'maxPage' => $maxPage,
				));
    }
}