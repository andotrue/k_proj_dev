<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function indexAction()
    {
    $items = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:Item')
        ->findAll();
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

}
