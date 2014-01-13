<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Doctrine\Common\Collections\Criteria;
class ShopController extends BaseController
{
    public function indexAction()
    {
    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findAll();
	return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items));
    }
    public function indexSortAction($field){
        if($field == "orderByOld"){
            $sort = "DESC";
            $field = "createdAt";
        }else{
            $sort = "ASC";
            $field = $field;
        }
//        $field = "itemCode";
        $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
//        ->findBy(array(),array('id'=>$sort));
        ->findBy(array(),array($field=>$sort));
    return $this->render('MachigaiGameBundle:Shop:index.html.twig',array('items'=>$items));
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
    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findBy(array('id'=>$id));
	return $this->render('MachigaiGameBundle:Shop:download.html.twig',array('items'=>$items));
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
*/    }
}
