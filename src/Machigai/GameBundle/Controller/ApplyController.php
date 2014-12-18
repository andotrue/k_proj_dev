<?php

namespace Machigai\GameBundle\Controller;

use Machigai\GameBundle\Controller\BaseController;

class ApplyController extends BaseController
{
    public function showAction($pageName)
    {
      // ログイン必須
      $request = $this->get('request');
      $cookies = $request->cookies;
      $smartContract = $cookies->get('smartContract'); 
      if(!$this->DEBUG && (empty($smartContract) || $smartContract != "true") ){

        $session = $request->getSession();
        $session->set('puzzlelp_access', 'true');
        return $this->redirect($this->generateUrl('response_token'));
      }

      return $this->render('MachigaiGameBundle:ApplyShow:' . $pageName . '.twig');
    }

    public function sendDataAction()
    {
      // ログイン必須
      $request = $this->get('request');
      $cookies = $request->cookies;
      $smartContract = $cookies->get('smartContract'); 
      if(!$this->DEBUG && (empty($smartContract) || $smartContract != "true") ){

        $session = $request->getSession();
        $session->set('puzzlelp_access', 'true');
        return $this->redirect($this->generateUrl('response_token'));
      }
      
      // POSTデータよりメール本文の作成
      // ルールとして
      // [name][改行]
      // [vale][改行]
      // [改行]
      // となるようにする。つまりnameは日本語になる
      // 
      // デフォルトパラメータ
      // title : 応募タイトル
      
      $title = $request->request->get('title');
      
      $content = $this->createMailContent($request->request);
      
      $body = $content;
      
      // メール送信処理
			$message = \Swift_Message::newInstance()
	        ->setSubject('【まちがいさがし放題】応募_' . $title)
//	        ->setFrom('support@machigai.puzzle-m.net')
//	        ->setTo('support@machigai.puzzle-m.net')
	        ->setFrom('support@machigai.puzzle-m.net')
	        ->setTo('yamane@hubase-i.net')
	        ->setBody($body);
	        
	        $this->get('mailer')->send($message);
      
      // 
      return $this->redirect($this->generateUrl('apply_complete'));
      
    }
    
    function completeAction(){
      return $this->render('MachigaiGameBundle:Apply:complete.html.twig');
    }
    
    function createMailContent($request){
      
      $datas = $request->all();
      
      $body = "";
      foreach($datas as $key => $val){
        
        $body = $body . $key . "\n";
        $body = $body . "　" . $val . "\n";
        $body = $body . "\n";
      }
      
      return $body;
    }
    
}
