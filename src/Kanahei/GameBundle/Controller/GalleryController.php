<?php

namespace Kanahei\GameBundle\Controller;
use Kanahei\GameBundle\Entity\PurchaseHistory;
use Kanahei\GameBundle\Entity\Log;
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
		$repository = $this->getDoctrine()  
							->getRepository('KanaheiGameBundle:PurchaseHistory');  
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
				'KanaheiGameBundle:Gallery:index.html.twig',
				array(
						'user'=>$user,
						'purchasedItems'=>$purchasedItems,
						'purchasedItems2'=>$purchasedItems2,
						'page' => $page,
						'maxPage' => $maxPage,
				));
    }
    
    public function deleteAction()
    {
    	$request = $this->get('request');
    	$checks = $request->request->get('check', "");
    	 
    	var_dump($checks);
    	foreach($checks as $key => $val)
    	{
$logger = $this->get('logger');
$logger->info($val);
    		$em = $this->getDoctrine()->getManager();
    		$entity = $em->getRepository('KanaheiGameBundle:PurchaseHistory')->find($val);
    		
    		$em->remove($entity);
    		$em->flush();
    	}
    	
    	return $this->redirect($this->generateUrl('Gallery'));
    	
    }
}