<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
	public function getUser()
	{        
        $session = $this->get('session');
        $id = $session->get('id');
        if(!empty($id)){
        	$user = $this->getDoctrine()
	        ->getRepository('MachigaiGameBundle:User')
	        ->find($id);
			return $user;
        }
        if(empty($id) ) {
        	//GUESTの場合NULLを返す
            $user = NULL;
            return $user;
        }
	}
    public function getPurchasedItems(){
        $user = $this->getUser();
        $user_id = $user->getId();
        $pre_purchasedItems = $this->getDoctrine()
        ->getRepository('MachigaiGameBundle:PurchaseHistory')
        ->findByUser($user_id);
        $purchasedItems = array();
        foreach ($pre_purchasedItems as $purchasedItem) {
            $times = $purchasedItem->getCreatedAt();
            foreach ($times as $from) {
                $to = date( "Y-m-d H:i:s", time());
                $fromSec = strtotime($from);
                $toSec   = strtotime($to);
                $differences = $toSec - $fromSec;
                //30days
                if($differences < 2592000){
                    $purchasedItems[] = $purchasedItem->getItem()->getId();
                }
            }       
        }
        return $purchasedItems;
    }
}