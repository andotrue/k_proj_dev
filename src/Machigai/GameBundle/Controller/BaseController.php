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
            $purchasedItems[] = $purchasedItem->getItem()->getId();
        }

        return $purchasedItems;
//      var_dump($purchasedItems);
//      exit;
    }
}