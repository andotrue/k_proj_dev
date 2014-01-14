<?php

namespace Machigai\GameBundle\Controller;

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

        $purchaseHistory = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:PurchaseHistory')
        ->findAll();


        if($field == "orderByOld"){
            $sort = "DESC";
            $field = "createdAt";
        }else{
            $sort = "ASC";
            $field = $field;
        }
        $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findBy(array(),array($field=>$sort));
    return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items,'user'=>$user));
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

    public function confirmAction()
    {
	return $this->render('MachigaiGameBundle:Shop:confirm.html.twig');
    }
    public function downloadExecuteAction($id){
        $user = $this->getUser();
        $itemPoint = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findOneById($id)->getConsumePoint();
        $purchasedItems = $this->getPurchasedItems();


        if(in_array($id,$purchasedItems)){
            $this->download();
        }else{
            $remainder = $user->getCurrentPoint()-$itemPoint;
            var_dump($remainder);
            exit;

            $em = $this->getDoctrine()->getEntityManager();
            $user = $em->getRepository('MachigaiGameBundle:User')->find($userId);
            $user->setNickName($nickname);
            $em->flush();
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
