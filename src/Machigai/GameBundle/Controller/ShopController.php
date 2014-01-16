<?php

namespace Machigai\GameBundle\Controller;
use Machigai\GameBundle\Entity\PurchaseHistory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Doctrine\Common\Collections\Criteria;
class ShopController extends BaseController
{
    public function indexAction()
    {
    $user = $this->getUser();
    $purchasedItems = $this->getPurchasedItems();

    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findAll();
	return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items,'user'=>$user,'purchasedItems'=>$purchasedItems));
    }
    public function indexSortAction($field){
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
    return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items,'user'=>$user,'purchasedItems'=>$purchasedItems));
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
    public function downloadExecuteAction($id){
        $user = $this->getUser();
        $item = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findOneById($id);
        $purchasedItems = $this->getPurchasedItems();

        $itemPoint = $item->getConsumePoint();
        if(in_array($id,$purchasedItems)){
            $this->download();
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
          
            $this->download();
        }
    }
    public function download(){
        //ダウンロード
        $image_file = dirname(__FILE__).'/../Resources/questions/1/105/MS00105_1.png';
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $image_file);
        header('Content-Length:' . filesize($image_file));
        header('Pragma: no-cache');
        header('Cache-Control: no-cache');
        readfile($image_file);
        exit;
        
/*      ブラウザ出力
        $file = dirname(__FILE__).'/../Resources/questions/1/105/MS00105_1.png';
        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Type', 'image/png');
        return  $response->send();
*/      }
}
