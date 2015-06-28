<?php

namespace Kanahei\GameBundle\Controller;
use Kanahei\GameBundle\Entity\PurchaseHistory;
use Kanahei\GameBundle\Entity\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShopController extends BaseController
{
    public function indexAction()
    {
        return $this->indexSortAction("2");
    }
    public function indexSortAction($field){
        $request= $this->get('request');
        $categoryCode = $request->query->get("categoryCode");
        $page = $request->query->get("page");
        $user = $this->getUser();
        $purchasedItems = $this->getPurchasedItems();
        $pageCount = 3;
        
        // カテゴリーコード
        if(empty($categoryCode)){
            $categoryCode = 2;
        }
        
        // ページ
        if(empty($page)){
            $page = 1;
        }

        $count = count(
            $this->getDoctrine()
                ->getRepository('KanaheiGameBundle:Item')
                ->findBy(array("category" => $categoryCode))
        );
        $maxPage = ceil($count / $pageCount);
        
        $offset_base = $page -1;
        $offset = $offset_base * $pageCount;

        //配布開始日で降順
        $sort = "DESC";
        $fieldName = "distributedFrom";

        $items = $this->getDoctrine()
        				->getRepository('KanaheiGameBundle:Item')
        				->findBy(
            				array("category" => $categoryCode),
            				array($fieldName=>$sort),
            				$pageCount,
            				$offset
        				);

		$groups = $this->getDoctrine()
						->getRepository('KanaheiGameBundle:Item')->createQueryBuilder('p')
						->where('p.category = :category_code')
						->groupBy('p.group')
						->setParameter('category_code', $categoryCode)
						->getQuery()
						->getResult();

        return $this->render(
            			'KanaheiGameBundle:Shop:index.html.twig',
            			array(
            				'items'=>$items,
                			'sortId'=> $field,
                			'categoryCode'=>$categoryCode,
                			'user'=>$user,
                			'purchasedItems'=>$purchasedItems,
                			'page' => $page,
                			'maxPage' => $maxPage,
                			'field' => $field,
            				'groups'=>$groups,
            			));
    }

    public function moreAction($categoryCode, $groupCode)
    {
    	$request= $this->get('request');
        $page = $request->query->get("page");
        $user = $this->getUser();
        $purchasedItems = $this->getPurchasedItems();
        $pageCount = 5;
        
        // カテゴリーコード
        if(empty($categoryCode)){
            $categoryCode = 2;
        }
        
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
	        $entity = $em->getRepository('KanaheiGameBundle:ItemGroup')->find($groupCode);
        	$groupname = $entity->getName();
        }

        
        //全件数取得
        $count = count(
            $this->getDoctrine()
                ->getRepository('KanaheiGameBundle:Item')
                ->findBy($where)
        );
        $maxPage = ceil($count / $pageCount);
        
        $offset_base = $page -1;
        $offset = $offset_base * $pageCount;

        //配布開始日で降順
        $sort = "DESC";
        $fieldName = "distributedFrom";
		
        $items = $this->getDoctrine()
        				->getRepository('KanaheiGameBundle:Item')
        				->findBy(
        					$where,
            				array($fieldName=>$sort),
            				$pageCount,
            				$offset
        				);

		return $this->render(
				'KanaheiGameBundle:Shop:more.html.twig',
				array(
						'items'=>$items,
						'categoryCode'=>$categoryCode,
						'user'=>$user,
						'purchasedItems'=>$purchasedItems,
						'page' => $page,
						'maxPage' => $maxPage,
						'groupCode' => $groupCode,
						'groupname' => $groupname,
				));
    }

    public function searchAction($categoryCode)
    {
    	$request = $this->get('request');
        $page = $request->query->get("page");
        $word = $request->query->get("word");
        $word = $request->request->get('word', $word);
        
        $user = $this->getUser();
        $purchasedItems = $this->getPurchasedItems();
        $pageCount = 5;
        
        // カテゴリーコード
        if(empty($categoryCode)){
            $categoryCode = 2;
        }
        
        // ページ
        if(empty($page)){
            $page = 1;
        }

    	$where = array("category" => $categoryCode);
    	$groupname = "フリーワード検索";

        
        $offset_base = $page -1;
        $offset = $offset_base * $pageCount;

        //配布開始日で降順
        $sort = "DESC";
        $fieldName = "distributedFrom";
		
        //全件数取得
        $repository = $this->getDoctrine()
						        ->getRepository('KanaheiGameBundle:Item');
        $query = $repository->createQueryBuilder('i')
						        ->where('i.category = :category', 'i.name LIKE :word')
						        ->setParameter('category', $categoryCode)
						        ->setParameter('word', "%$word%")
						        ->orderBy('i.distributedFrom', 'DESC')
						        ->getQuery();
        $count = count($query->getResult());
        
        //最大ページ数
        $maxPage = ceil($count / $pageCount);
        
        //表示データの取得
        $repository = $this->getDoctrine()
					        ->getRepository('KanaheiGameBundle:Item');
        $query = $repository->createQueryBuilder('i')
						        ->where('i.category = :category', 'i.name LIKE :word')
						        ->setParameter('category', $categoryCode)
						        ->setParameter('word', "%$word%")
						        ->orderBy('i.distributedFrom', 'DESC')
						        ->setMaxResults($pageCount)
						        ->setFirstResult($offset)
						        ->getQuery();
        $items = $query->getResult();
        
        
		return $this->render(
				'KanaheiGameBundle:Shop:search.html.twig',
				array(
						'items'=>$items,
						'categoryCode'=>$categoryCode,
						'user'=>$user,
						'purchasedItems'=>$purchasedItems,
						'page' => $page,
						'maxPage' => $maxPage,
						'groupname' => $groupname,
						'word' => $word, 
				));
    }

    public function downloadAction($id)
    {
    	//ダウンロード履歴のアイテムID配列の取得
    	$purchasedItems = $this->getPurchasedItems();
    	
    	$user = $this->getUser();
	    $items = $this->getDoctrine()
	        ->getRepository('KanaheiGameBundle:Item')
	        ->findBy(array('id'=>$id));
		return $this->render(
						'KanaheiGameBundle:Shop:download.html.twig',
						array(
							'items'=>$items,
							'user'=>$user,
							'dlcount' => count($purchasedItems),
						)
					);
    }

    public function errorAction()
    {
		return $this->render('KanaheiGameBundle:Shop:error.html.twig');
    }

    public function confirmAction($id)
    {
    	//ダウンロード履歴のアイテムID配列の取得
    	$purchasedItems = $this->getPurchasedItems();
    	
    	return $this->render(
    					'KanaheiGameBundle:Shop:confirm.html.twig',
    					array(
    						'id'=>$id,
    						'dlcount' => count($purchasedItems),
    					)
    			);
    }

	public function downloadExecuteAction($id){

		$request = $this->get('request');
		$session = $request->getSession();  

        $syncToken = $request->query->get("syncToken");
        $mode = $request->query->get("mode");

		if(!empty($syncToken)){
			$users = $this->getDoctrine()
					->getManager()
					->getRepository('KanaheiGameBundle:User')->findBy(array('syncToken' =>$syncToken));
		} else {
 			$userId = $session->get("id");
			if( !empty($userId) ){
				$em = $this->getDoctrine()->getManager();
				$user = $em->getRepository('KanaheiGameBundle:User')->find($userId);
				$users = array($user);
			}
		}
		
		if( empty($users) ) {
			//ゲストユーザの場合は何もしない。   
		}else{
			$user = $users[0];
			$session->set('auId', $user->getAuId());
			$session->set('id',  $user->getId());
			$session->set('smartPassResult', true );
		}
		
        $user = $this->getUser();
        
        //アイテムオブジェクト取得
        $item = $this->getDoctrine()
        ->getRepository('KanaheiGameBundle:Item')
        ->findOneById($id);
        //アイテム画像パス取得
        $categoryCode = $item->getCategory()->getCategoryCode();
        $itemPath = $item->getItemPath();
        if($categoryCode==1)
        {
            $itemPath = "/../Resources/public/images/wallpaper/".$itemPath.".png";
        }
        elseif($categoryCode==2){
            $itemPath = "/../Resources/public/images/stamp/".$itemPath.".png";
        }
	    elseif($categoryCode==3){
            $itemPath = "/../Resources/public/images/animestamp/".$itemPath.".png";
        }
        //消費ポイントの取得
        $itemPoint = $item->getConsumePoint();
        
		$alreadyBuy = $session->get('buy_'.$categoryCode . "_" . $id);
		
        //ダウンロード履歴のアイテムID配列の取得
        $purchasedItems = $this->getPurchasedItems();
        
        //再ダウンロードなら
        if(in_array($id,$purchasedItems) || $alreadyBuy)
        {
            if( empty($mode) ||  $mode != 'file')
            {
                return $this->render(
                		'KanaheiGameBundle:Shop:downloadedContentView.html.twig',
                		array(
                			'id'=>$id, 
                			'syncToken'=> $syncToken, 
                			'mode' => 'file'
                				
                		)
                	);
            }
            else
            {
                return $this->download($itemPath);
            }
        }
        else{
			
			$remainder = $user->getCurrentPoint()-$itemPoint;

			$purchasedInfo = new PurchaseHistory();
			$purchasedInfo->setUser($user);
			$purchasedInfo->setItem($item);
			$purchasedInfo->setPointBeforePurchase($user->getCurrentPoint());
			$purchasedInfo->setPointAfterPurchase($remainder);
			$purchasedInfo->setConsumePoint($itemPoint);
			$purchasedInfo->setCreatedAt(date("Y-m-d H:i:s"));
			$purchasedInfo->setUpdatedAt(date("Y-m-d H:i:s"));

			$em = $this->getDoctrine()->getManager();
			$em->persist($purchasedInfo);
			$em->flush();

			$log = new Log();
			$log->setUserId($user->getId());
			$log->setType("point");
			$log->setName("shop_use_point: " .$itemPoint);
			$log->setCreatedAt(date("Y-m-d H:i:s"));
			$em->persist($log);
			$em->flush();

			$em = $this->getDoctrine()->getManager();
			$user_id = $em->getRepository('KanaheiGameBundle:User')->find($user->getId());
			$user_id->setCurrentPoint($remainder);
			$em->flush();	
			
			$session->set('buy_'.$categoryCode . "_" . $id, true );

            if( empty($mode) ||  $mode != 'file'){
                return $this->render(
                				'KanaheiGameBundle:Shop:downloadedContentView.html.twig',
                				array(
                					'id'=>$id, 
                					'syncToken'=> $syncToken, 
                					'mode' => 'file',
                					'dlcount' => count($purchasedItems),
                				)
                		);
            }else{
                return $this->download($itemPath);
            }
        }
    }

	public function downloadExecuteWVAction($id)
	{

		$request = $this->get('request');
	    $mode = $request->query->get("mode");
	    
	    $item = $this->getDoctrine()
	      ->getRepository('KanaheiGameBundle:Item')
	      ->findOneById($id);
	    
	    $categoryCode = $item->getCategory()->getCategoryCode();
	    $itemPath = $item->getItemPath();
	    if($categoryCode==1)
	    {
	        $itemPath = "/../Resources/public/images/wallpaper/".$itemPath.".png";
	    }
	    elseif($categoryCode==2)
	    {
	        $itemPath = "/../Resources/public/images/stamp/".$itemPath.".png";
	    }
	    elseif($categoryCode==3)
	    {
	    	$itemPath = "/../Resources/public/images/animestamp/".$itemPath.".png";
	    }
	     
	    if( empty($mode) ||  $mode != 'file')
	    {
	        return $this->render('KanaheiGameBundle:Shop:downloadedContentViewWV.html.twig',array('id'=>$id, 'mode' => 'file'));
	    }
	    else{
	        return $this->download($itemPath);
	    }
	}
    
    public function download($itemPath)
    {
        $image_file = dirname(__FILE__).$itemPath;
		$filename = basename($image_file);

        $response = new BinaryFileResponse($image_file);
		$response->trustXSendfileTypeHeader();

		return $response;
	}
}