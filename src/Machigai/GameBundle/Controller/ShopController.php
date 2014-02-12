<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\PurchaseHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Response;

class ShopController extends BaseController
{
    public function indexAction()
    {
    $user = $this->getUser();
    $purchasedItems = $this->getPurchasedItems();

    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findAll();
	return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items, 'categoryCode'=>1, 'user'=>$user,'purchasedItems'=>$purchasedItems));
    }
    public function indexSortAction($field){
        $request= $this->get('request');
        $categoryCode = $request->query->get("categoryCode");
        $user = $this->getUser();
        $purchasedItems = $this->getPurchasedItems();
        $purchaseHistory = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:PurchaseHistory')
        ->findAll();

        if($field == "orderByOld"){
            $sort = "DESC";
            $field = "createdAt";
        }elseif($field == "popularity"){
            $sort = "ASC";
            $field = "popularityRank";
        }else{
            $sort = "ASC";
            $field = $field;
        }
        $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findBy(array(),array($field=>$sort));
    return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items, 'categoryCode'=>$categoryCode,'user'=>$user,'purchasedItems'=>$purchasedItems));
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
        $itemPoint = $item->getConsumePoint();
        if(in_array($id,$purchasedItems)){
            return $this->download($itemPath);
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

            $em = $this->getDoctrine()->getEntityManager();
            $user_id = $em->getRepository('MachigaiGameBundle:User')->find($user->getId());
            $user_id->setCurrentPoint($remainder);
            $em->flush();

            return $this->download($itemPath);
        }
    }
	
    public function download($itemPath){

        $image_file = dirname(__FILE__).$itemPath;
		
        $response = new Response($image_file);
		$response->headers->set('Content-type', 'application/octect-stream');
		$response->headers->set('Cache-Control', 'private');
		$response->headers->set('Content-type', mime_content_type($image_file));
		$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($image_file) . '"');
		$response->headers->set('Content-length', filesize($image_file));
		$response->sendHeaders();
		$response->setContent(readfile($image_file));
		
		return $response;
	}
}
