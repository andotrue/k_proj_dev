<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\PurchaseHistory;
use Machigai\GameBundle\Entity\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShopController extends BaseController
{
    public function indexAction()
    {
    $request= $this->get('request');
    $categoryCode = $request->query->get("categoryCode");

    $user = $this->getUser();
    $purchasedItems = $this->getPurchasedItems();
    $purchaseHistory = $this->getDoctrine()
    ->getRepository('MachigaiGameBundle:PurchaseHistory')
    ->findAll();

    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findBy(array(),array("id"=>"DESC"));
	return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items, 'sortId'=> '0', 'categoryCode'=>1, 'user'=>$user,'purchasedItems'=>$purchasedItems));
    }
    public function indexSortAction($field){
        $request= $this->get('request');
        $categoryCode = $request->query->get("categoryCode");
        $user = $this->getUser();
        $purchasedItems = $this->getPurchasedItems();
        $purchaseHistory = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:PurchaseHistory')
        ->findAll();

        if($field == "1"){
            $sort = "ASC";
            $fieldName = "name";
        }elseif($field == "2"){
            $sort = "DESC";
            $fieldName = "distributedFrom";

        }elseif($field == "3"){
            $sort = "ASC";
            $fieldName = "distributedFrom";

        }elseif($field == "4"){
            $sort = "ASC";
            $fieldName = "popularityRank";

        }else{
            $sort = "DESC";
            $fieldName = 'id';
        }
        $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findBy(array(),array($fieldName=>$sort));

    return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items, 'sortId'=> $field, 'categoryCode'=>$categoryCode,'user'=>$user,'purchasedItems'=>$purchasedItems));
    }

    public function wallpaperAction()
    {
	return $this->render('MachigaiGameBundle:Shop:wallpaper.html.twig');
    }

    public function stampAction()
    {
	return $this->render('MachigaiGameBundle:Shop:stamp.html.twig');
    }

    public function downloadAction($id)
    {
    $user = $this->getUser();
    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findBy(array('id'=>$id));
	return $this->render('MachigaiGameBundle:Shop:download.html.twig',array('items'=>$items,'user'=>$user));
    }

    public function errorAction()
    {
	return $this->render('MachigaiGameBundle:Shop:error.html.twig');
    }

    public function confirmAction($id)
    {
	return $this->render('MachigaiGameBundle:Shop:confirm.html.twig',array('id'=>$id));
    }

	public function download2(){

	}

	public function downloadExecuteAction($id){

		$request = $this->get('request');
		$session = $request->getSession();  

        $syncToken = $request->query->get("syncToken");
        $mode = $request->query->get("mode");

		if(!empty($syncToken)){
			$users = $this->getDoctrine()
					->getManager()
					->getRepository('MachigaiGameBundle:User')->findBy(array('syncToken' =>$syncToken));
		} else {
 			$userId = $session->get("id");
			if( !empty($userId) ){
				$em = $this->getDoctrine()->getEntityManager();
				$user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
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
        
        $item = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findOneById($id);
        $purchasedItems = $this->getPurchasedItems();
        $categoryCode = $item->getCategory()->getCategoryCode();
        $itemPath = $item->getItemPath();
        if($categoryCode==1){
            $itemPath = "/../Resources/public/images/wallpaper/".$itemPath.".png";
        }elseif($categoryCode==2){
            $itemPath = "/../Resources/public/images/stamp/".$itemPath.".png";
        }
		
		$alreadyBuy = $session->get('buy_'.$categoryCode . "_" . $id);
		
        $itemPoint = $item->getConsumePoint();
        if(in_array($id,$purchasedItems) || $alreadyBuy){
            if( empty($mode) ||  $mode != 'file'){
                return $this->render('MachigaiGameBundle:Shop:downloadedContentView.html.twig',array('id'=>$id, 'syncToken'=> $syncToken, 'mode' => 'file'));
            }else{
                return $this->download($itemPath);
            }
        }else{
			
			$remainder = $user->getCurrentPoint()-$itemPoint;

			$purchasedInfo = new PurchaseHistory();
			$purchasedInfo->setUser($user);
			$purchasedInfo->setItem($item);
			$purchasedInfo->setPointBeforePurchase($user->getCurrentPoint());
			$purchasedInfo->setPointAfterPurchase($remainder);
			$purchasedInfo->setConsumePoint($itemPoint);
			$purchasedInfo->setCreatedAt(date("Y-m-d H:i:s"));
			$purchasedInfo->setUpdatedAt(date("Y-m-d H:i:s"));

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($purchasedInfo);
			$em->flush();

			$log = new Log();
			$log->setUserId($user->getId());
			$log->setType("point");
			$log->setName("shop_use_point: " .$itemPoint);
			$log->setCreatedAt(date("Y-m-d H:i:s"));
			$em->persist($log);
			$em->flush();

			$em = $this->getDoctrine()->getEntityManager();
			$user_id = $em->getRepository('MachigaiGameBundle:User')->find($user->getId());
			$user_id->setCurrentPoint($remainder);
			$em->flush();	
			
			$session->set('buy_'.$categoryCode . "_" . $id, true );

            if( empty($mode) ||  $mode != 'file'){
                return $this->render('MachigaiGameBundle:Shop:downloadedContentView.html.twig',array('id'=>$id, 'syncToken'=> $syncToken, 'mode' => 'file'));
            }else{
                return $this->download($itemPath);
            }
        }
    }

    public function download($itemPath){

        $image_file = dirname(__FILE__).$itemPath;
		$filename = basename($image_file);

        $response = new BinaryFileResponse($image_file);
		$response->trustXSendfileTypeHeader();

		return $response;
	}
}