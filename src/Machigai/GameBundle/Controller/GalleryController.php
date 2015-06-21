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
        
        $em = $this->getDoctrine()->getManager();
        $user_id = $em->getRepository('MachigaiGameBundle:User')->find($user->getId());
        $purchasedItems = $this->getDoctrine()
        						->getRepository('MachigaiGameBundle:PurchaseHistory')
        						->findByUser($user_id);
        
        $pageCount = 5;
        
        // カテゴリーコード
        $categoryCode = 1;
        $groupCode = "new";
        
        // ページ
        if(empty($page)){
            $page = 1;
        }

        if($groupCode == "new")
        {
        	$where = array("category" => $categoryCode);
        	$groupname = "新着";
        }
        else
        {
        	$where = array("category" => $categoryCode, "group" => $groupCode);
        	$em = $this->getDoctrine()->getManager();
	        $entity = $em->getRepository('MachigaiGameBundle:ItemGroup')->find($groupCode);
        	$groupname = $entity->getName();
        }

        
        //全件数取得
        $count = count(
            $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:Item')
                ->findBy($where)
        );
        $maxPage = ceil($count / $pageCount);
        
        $offset_base = $page -1;
        $offset = $offset_base * $pageCount;

        //配布開始日で降順
        $sort = "DESC";
        $fieldName = "distributedFrom";
		
        $items = $this->getDoctrine()
        				->getRepository('MachigaiGameBundle:Item')
        				->findBy(
        					$where,
            				array($fieldName=>$sort),
            				$pageCount,
            				$offset
        				);

		return $this->render(
				'MachigaiGameBundle:Gallery:index.html.twig',
				array(
						'items'=>$items,
						'categoryCode'=>$categoryCode,
						'user'=>$user,
						'purchasedItems'=>$purchasedItems,
						'purchasedItems2'=>$purchasedItems2,
						'page' => $page,
						'maxPage' => $maxPage,
						'groupCode' => $groupCode,
						'groupname' => $groupname,
				));
		
   	
        //return $this->render('MachigaiGameBundle:Shop:more.html.twig');
    }
}