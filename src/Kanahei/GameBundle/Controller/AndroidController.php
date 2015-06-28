<?php

namespace Kanahei\GameBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Kanahei\GameBundle\Entity\User;
use Kanahei\GameBundle\Entity\Regist;
use Kanahei\GameBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\File\Exception\Exception;
use Kanahei\GameBundle\Entity\Log;
use \DateTime;


include_once "Auth/OpenID.php";
include_once "Auth/OpenID/FileStore.php";
include_once "Auth/OpenID/Consumer.php"; 

use \Auth_OpenID_FileStore;
use \Auth_OpenID;
use \Auth_OpenID_Consumer;

class AndroidController extends BaseController
{
    public $connectTo = "connect.auone.jp";

    public function auIdAction()
  {
        if($this->AUID_DEBUG)return $this->redirect( $this->generateUrl ("Login") );

        $realm = "https://kanahei.puzzle-m.net:443/";               
        $returnToUrl = "https://kanahei.puzzle-m.net/auIdAssociation";   
        $connectTo = "https://connect.auone.jp";                   

		if($this->DEBUG){
			$this->connectTo = "st.connect.auone.jp";
			$realm = "http://st.kanahei.puzzle-m.net:80/";        
			$returnToUrl = "http://st.kanahei.puzzle-m.net/auIdAssociation";   
			$connectTo = "https://st.connect.auone.jp";                   
		}
		
        $logger = $this->get('logger');
        $logger->info('in auIdAction');
        $formId = "test";
       
        $associationDirPath = "/tmp";                              
        $preDealPath  = "/net/id/hny_rt_net/cca?ID=auOneOpenIDOther";       
        $authUrl = $connectTo . $preDealPath;

       
       
        // アソシエーションを保存するストアを作成                  
        $logger->info(' new Auth_OpenID_FileStore');               
        $store = new Auth_OpenID_FileStore($associationDirPath);   
       
        // RP(Consumer)のインスタンスを生成
        $logger->info(' new Auth_OpenID_Consumer');
        $consumer =& new Auth_OpenID_Consumer($store);             
       
        //認証方法に該当する URI をセットし、Discovery などを実行  
        $logger->info(' $consumer->begin(' . $authUrl . ')');      
        $auth_request = $consumer->begin($authUrl);                
       
        // SREG・PAPE 等の OpenID 拡張機能を利用する場合は、ここでその処理を追加。
        // 詳細については、ライブラリに添付のサンプルコードを参照してください。
        // OP が OpenID1.0 しかサポートしない場合には、常にリダイレクトを行います。
        // KDDI が加盟店向けに提供する機能は、OpenID2.0 に準拠したものとなります。

  //      $logger->info(" $auth_request->shouldSendRedirect()");
        if ($auth_request->shouldSendRedirect()) {                 
       
            $logger->info(' true: redirect');                      
       
           // リダイレクト先 URL を取得。                          
           $redirect_url = $auth_request->redirectURL($realm, $returnToUrl);
            if (Auth_OpenID::isFailure($redirect_url)) {
            // Discovery 処理などが失敗した場合には、ここでエラー処理(エラー画面の表示など)を行う。
                return $this->redirect($this->generateUrl('Error'));                   
            } else {
            // リダイレクトを実行。 header("Location: ".$redirect_url);        
            //OpenID 認証要求
                //TODO:リダイレクト方法を検証する
                $logger->info(' false: Auth_OpenID::isFailure');
                return $this->redirect($redirect_url);
            }
        } else {
            $logger->info(' false: redirect');
        // OpenID2.0 の場合は常に自動 POST 。
        // 以下、自動ポストのサンプル。携帯電話(EZ 端末)等のケースで、OpenID2.0 であってもリダイレクトを
        // 行いたい場合には、上のリダイレクト処理を実行すること。 $form_id = 'openid_message';
            $form_html = $auth_request->htmlMarkup( $realm,
                                                $returnToUrl,
                                                false,// OpenID の immediate モードを使用するかどうか。
                                                array('id' => $formId) // フォームに追加設定する
                                                    // attribute のリスト例
                                                );

            if (Auth_OpenID::isFailure($form_html)) {
                $logger->info(' true: Auth_OpenID::isFailure');
                // Discovery 処理などが失敗した場合には、ここでエラー処理(エラー画面の表示など)を行う。
            } else {
                $logger->info(' false: Auth_OpenID::isFailure -> first success.');
                // HTML を表示。
                $logger->info($form_html);
                return new Response($form_html);
            }
        }
    }

    public function auIdAssociationAction(){

		session_start();
		
        $logger = $this->get('logger');
        $logger->info('inf auIdAssociationAction');
        $associationDirPath = "/tmp";
        $return_to = "/auIdComplete";
        // RP(Consumer)のインスタンス生成までは認証リクエスト時と同じ
        $store = new Auth_OpenID_FileStore($associationDirPath);
        $consumer =& new Auth_OpenID_Consumer($store);
        // Return_to を取得セットして、認証を完了(OP-Identifier による認証時の再 Discovery 等)
        $response = $consumer->complete($return_to);
/*
        if ($response->status == Auth_OpenID_CANCEL) { // Cancel メッセージが帰ってきた場合の処理
        } else if ($response->status == Auth_OpenID_FAILURE) { // エラーが帰ってきた場合の処理
            return $this->redirect("Error");
        } else if ($response->status == Auth_OpenID_SUCCESS) { // 認証成功。以下の方法でユーザの OpenID を取得
*/      //    $openid = $response->getDisplayIdentifier();
            //ユーザを探す。
	        $request = $this->get("request");
            $openId = $request->query->get("openid_claimed_id");
            $logger->info("openid.claimed_id = $openId");

			// メールアドレス会員の場合
			$session = $request->getSession();
 			$userId = $session->get("id");
			if( !empty($userId) ){
				$em = $this->getDoctrine()->getManager();
				$user = $em->getRepository('KanaheiGameBundle:User')->find($userId);
				
				// 先にauIDログインをしてしまった会員の為のデータ統合
				$auid_users = $em->getRepository('KanaheiGameBundle:User')->findBy(array(
					'auId' => $openId
				));
				if( !empty($auid_users) ){
					$auid_user = $auid_users[0];
					$user->mergeUserData($auid_user, $em);
					$em->remove($auid_user);
				}
				$user->setAuId($openId);
				$em->flush();
				$session->remove("id");
			}
			
            $users = $this->getDoctrine()
                ->getRepository('KanaheiGameBundle:User')
                ->findBy(array("auId"=>$openId));
            if(empty($users)){
                //ユーザのニックネーム登録ページヘ。
                //シンクトークン発行
                $syncToken = uniqid();
                $session = $request->getSession();
                $session->set('syncTokenPre', $syncToken);
				$session->set("openId", $openId);

                return $this->redirect($this->generateUrl('AndroidRegisterEntry'));
            }else{
                $user = $users[0];
                $session = $request->getSession();
                $session->set('id', $user->getId());
    //            $session->set('auId', $auId);
                $syncToken = $user->getSyncToken();
                $nickname = $user->getNickname();

				$session->set('syncToken', $syncToken);
                $session->set('smartPassResult', true );

                //セッションを登録。
                return $this->render('KanaheiGameBundle:Android:afterAuIdLogin.html.twig', array('syncToken'=> $syncToken, 'nickname'=> $nickname));
            }
/*        } else {
            return $this->redirect("Error");
		}
 */   }

   /**
        au Id loginが完了した後の処理
        ・ユーザが登録されていればサーバでのログイン処理 
        ・登録されていなければニックネーム登録画面へ    
    */
    public function afterAuIdLoginAction(){
//    private function afterAuId($syncToken){
        //ユーザを探す。

        $request = $this->get("request");
        $logger = $this->get("logger");
        $logger->info("afterAuIdLoginAction");
        $syncToken = $request->query->get("syncToken");
        $logger->info("\$syncToken = " . $syncToken);
        if(empty($syncToken)){
            return  new Response('<html><body>エラー：トークンが存在しません。</body></html>');
        }
        $users = $this->getDoctrine()
            ->getRepository('KanaheiGameBundle:User')
            ->findBy(array("syncToken"=>$syncToken));
        if(empty($users)){
            //ユーザのニックネーム登録ページヘ。
            $session = $request->getSession();
            $syncToken = $session->get('syncTokenPre');

            return $this->redirect($this->generateUrl('AndroidRegisterEntry'));
        }else{
            //ユーザのログイン完了
            $user = $users[0];
            $session = $request->getSession();
            $session->set('id', $user->getId());
//            $session->set('auId', $auId);
            $session->set('syncToken', $syncToken);
            $session->set('smartPassResult', true );

            $syncToken = $user->getSyncToken();
            $nickname = $user->getNickname();


            //セッションを登録。
            return $this->render('KanaheiGameBundle:Android:afterAuIdLogin.html.twig', array('syncToken'=> $syncToken, 'nickname'=> $nickname));
//                $this->redirect('Top',array());
        }
    }



	public function getCommonAccessToken(){
		return 'h6C43S5SS7wMu7JNuy3LM8E4';
	}
    private function hasValidCommonToken($token){
        $result = ( $this->getCommonAccessToken() == $token );
        return $result;
    }

	public function indexAction(){
		$user = $this->getUserInfo();
		if (empty($user)){
			$json = array('error' =>'invalid user' );
			$response = new Response($json);
			$response->headers->set('Content-Type', 'application/json');
			return $response->send();
		}
		$request = $this->getRequest();
		$json = json_encode(array());
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response->send();
	}

	//ユーザ識別用トークンが必要
	public function userAction(){
        $request = $this->get("request");
        $syncToken = $request->query->get("token");
        $users = $this->getDoctrine()
        ->getRepository('KanaheiGameBundle:User')
        ->findBy(array("syncToken"=>$syncToken));

        if(empty($users))
			return $this->getErrorJsonResponse('Invalid User')->send();
        $user = $users[0];

//		$serializer = $this->get('jms_serializer');
//		$json = $serializer->serialize($user, 'json');
		$json = $user->toJsonForSync(); 
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	//ゲスト用トークンが必要
	public function noticesAction(){
        $request = $this->get("request");
        $syncToken = $request->query->get("token");

		//ゲスト用トークンチェック
		if(!$this->hasValidCommonToken($syncToken)){
            $response = $this->getErrorJsonResponse('Invalid User');
            return $response;
        }

		$news = $this->getDoctrine()
			->getRepository('KanaheiGameBundle:News')
      ->findBy(array(),array('startedAt'=>'DESC'));
      
		$html = "<ul>";
		for ($i=0; $i < sizeof($news); $i++) {
			$new = $news[$i];
			$html .= '<li>'.$new->getTitle() . $new->getDescription().'</li>';
		}
		$html .='</ul>';
		$response = new Response($html);
		$response->headers->set('Content-Type', 'text/html');
		$response->headers->set('Content-Type', 'utf8');
		return $response;
	}

	//ゲスト用トークンが必要
	public function gameAction($id, $uid){
/*		//ゲスト用トークンチェック
		if (!$this->hasValidCommonToken())
			return $response = $this->getErrorJsonResponse('Invalid User')->send();			
*/		

       $question = $this->getDoctrine()
        ->getRepository('KanaheiGameBundle:Question')
        ->find($id);

        $isError = false;
        $error = null;
        if( $question == null ){
            $error = array('error' => true, 'errorType' => 'NOT_EXIST', 'message' => "問題が存在しません。");
            $isError = true;
        }else{
            if( $question->getIsDelete() == true){
                $error = array('error' => true, 'errorType' => 'DELETED', 'message' => "問題は削除されました。");
                $isError = true;
            }
            if( $question->getDistributedFrom() > new DateTime() || new DateTime() > $question->getDistributedTo() ){
                $error = array('error' => true, 'errorType' => 'NOT_EXIST', 'message' => "問題が存在しません。");
                $isError = true;
            }
        }
        if ($isError == true){
            $json = json_encode($error);
            $response = new Response($json);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }


        $questionNumber = $question->getQuestionNumber();
        $failLimit = $question->getFailLimit();
        $timeLimit = $question->getTimeLimit();
        $clearPoint = $question->getClearPoint();
        $bonusPoint = $question->getBonusPoint();
        $level = $question->getLevel();
        $qcode = $question->getQcode();
        $questionTitle = $question->getQuestionTitle();

        $copyrightFileName = "";

        $user = $this->getUser();
/*       $user = $this->getDoctrine()
        ->getRepository('KanaheiGameBundle:User')
        ->find($uid);
*///        var_dump($user);
//        $token = $request->headers->get('X-CSRF-Token');
 //       var_dump($token);

        $questionArray = array(
                'questionId' => $question->getId(),
                'questionNumber' => $questionNumber,
                'failLimit' => $failLimit,
                'timeLimit' => $timeLimit,
                'clearPoint' => $clearPoint,
                'bonusPoint' => $bonusPoint,
                'level' => $level,
                'qcode' => $qcode,
                'questionTitle' => $questionTitle,
            );
        
        $userData = null;        
        //登録ユーザの場合
        if (!empty($user)){
            $userData = array(
                'userId' => $uid,
                'currentPoint' => $user->getCurrentPoint()
            );


            $playHistoryDB = $this->getDoctrine()
            ->getRepository('KanaheiGameBundle:PlayHistory')
            ->findBy(array('user' => $user , 'question'=> $question ));
        }
        //playHistoryデータがあった場合

        
        if (!empty($playHistoryDB)){
            $playHistory = array(
                'playHistoryId' => $playHistoryDB[0]->getId(), 
                'playStartedAt' => $playHistoryDB[0]->getPlayStartedAt(), 
                'playEndedAt' => $playHistoryDB[0]->getPlayEndedAt(),
                'clearTime' => $playHistoryDB[0]->getClearTime(),
                'suspendedTime' => $playHistoryDB[0]->getSuspendTime(),
                'isSavedGame' => $playHistoryDB[0]->getIsSavedGame(),
                'gameStatus' => $playHistoryDB[0]->getGameStatus(),
                'playInfo' => $playHistoryDB[0]->getPlayInfo(), // Javascriptでは playData
                );
        }else{
            $playHistory = null;       
        }
        if (!empty($user) && empty($playHistory)){
            $userData['isFirstTime'] = true;
        }else{
            $userData['isFirstTime'] = false;            
        }

        $playInfo = array('error'=> false, 'user' => $userData, 'question' => $questionArray, 'playHistory' => $playHistory);
        $json = json_encode($playInfo);
        $json = $json;
        $logger = $this->get('logger');
        $logger->info($json);
        $response = new Response($json);
//        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Content-Type', 'text/javascript');
        return $response;
	}

	//ゲスト用トークンが必要	
	public function gameDataAction($id, $file_name){
		//ゲスト用トークンチェック
		if(!$this->hasValidCommonToken()) 
			return $this->getErrorJsonResponse('Invalid User')->send();

		$request = $this->get('request');

        $types = array('xml' => '.xml', 'first' => '_1.png', 'second' => '_2.png');
        $format = $types[$type];
        $file = dirname(__FILE__).'/../Resources/questions/'.$level.'/'. $qcode . '/MS'. sprintf('%05d',$qcode). $format;

        $response = new BinaryFileResponse($file);
//        $response->prepare($request);
        if ($type == 'xml'){
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('charset', 'UTF-8');
        }else{
            $response->headers->set('Content-Type', 'image/png');
        }
        return  $response;

		$json = json_encode(array());
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

	public function getUserAction(){
		$request = $this->get('request');
		$sync_token = $request->query->get('token');

        if(!empty($sync_token)){
        	$users = $this->getDoctrine()
        	->getManager()
			->createQuery('SELECT u FROM KanaheiGameBundle:User u where u.syncToken = :sync_token')
			->setParameter('sync_token', $sync_token)
            ->getResult();  

            return $users[0];
        }else{
        	//GUESTの場合NULLを返す
            return NULL;
        }
	}
    public function gameFileAction($level,$qcode, $type){

        $types = array('xml' => '.xml', 'first' => '_1.png', 'second' => '_2.png', 'copyright' => 'copyright.png');
        $format = $types[$type];

        if ($type == 'copyright'){
            $file = dirname(__FILE__).'/../Resources/questions/'.$level.'/'. $qcode . '/'. $format;
        }else{
            $file = dirname(__FILE__).'/../Resources/questions/'.$level.'/'. $qcode . '/MS'. sprintf('%05d',$qcode). $format;            
        }

        try{
            $response = new BinaryFileResponse($file);
        }catch(FileNotFoundException $e){
            $logger = $this->get('logger');
            $logger->info("FileNotFound AndroidController.gameFileAcition:". $file);
            throw $this->createNotFoundException('The product does not exist');
        }
//        $response->prepare($request);
        if ($type == 'xml'){
            $response->headers->set('Content-Type', 'text/xml');
            $response->headers->set('charset', 'UTF-8');
        }else{
            $response->headers->set('Content-Type', 'image/png');
        }
        return  $response;
    }

	public function hasValidCommonTokenAction(){
		$request = $this->get('request');
		$request_token = $request->query->get('token');
		$common_token = $this->getCommonAccessToken();
		return ($common_token == $request_token);
	}

	public function getErrorJsonResponse($text){
		$json = json_encode(array('error'=> $text));
		$response = new Response($json);
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}

    public function getQuestionDataAction(){
        $logger = $this->get('logger');
        $logger->info("getQuestionDataAction:");
        $request = $this->get('request');
        $syncToken = $request->query->get('token');
        $questions = $this->getDoctrine()
            ->getRepository('KanaheiGameBundle:Question')
            ->findAll();
        $questionData = array();

        $users = $this->getDoctrine()
            ->getRepository('KanaheiGameBundle:User')
            ->findBy(array('syncToken' => $syncToken));
		
		if(empty($users)){
			$user = null;
		} else {
	        $user = $users[0];
		}

        for ($i = 0; $i < count($questions); $i++) {
            //問題の配信停止は、 $questionData['question'][$i]['is_delete']のフラグをフロントエンドで確認して行う。
			if($user == null){
				$playHistories = null;
			} else {
				$playHistories = $this->getDoctrine()
					->getRepository('KanaheiGameBundle:PlayHistory')
					->findBy(array('user' => $user , 'question'=> $questions[$i] ));
			}

            $questionData['question'][$i]['id'] = $questions[$i]->getId();
            $questionData['question'][$i]['questionNumber'] = $questions[$i]->getQuestionNumber();
            $questionData['question'][$i]['distributedFrom'] = $questions[$i]->getDistributedFrom()->format("Y-m-d H:i:s");
            $questionData['question'][$i]['distributedTo'] = $questions[$i]->getDistributedTo()->format("Y-m-d H:i:s");
            $questionData['question'][$i]['qcode'] = $questions[$i]->getQcode();
            $questionData['question'][$i]['level'] = $questions[$i]->getLevel();
            $questionData['question'][$i]['title'] = $questions[$i]->getQuestionTitle();
            $questionData['question'][$i]['otetsukiLimit'] = $questions[$i]->getFailLimit();
            $questionData['question'][$i]['clearPoint'] = $questions[$i]->getClearPoint();
            $questionData['question'][$i]['bonusPoint'] = $questions[$i]->getBonusPoint();
            $questionData['question'][$i]['timeLimit'] = $questions[$i]->getTimeLimit();
            if(!empty($playHistories)){
                $logger->info("getQuestionDataAction: playHistory exists.");
                $playHistory = $playHistories[0];
                $questionData['question'][$i]['playInfoData'] = $playHistory->getPlayInfo();
                $questionData['question'][$i]['status'] = $playHistory->getGameStatus();
                $questionData['question'][$i]['isSavedGame'] = $playHistory->getIsSavedGame();
            }else{
            $logger->info("getQuestionDataAction: playHistory is null.");
                $questionData['question'][$i]['playInfoData'] = null;
                $questionData['question'][$i]['status'] = "1";
                $questionData['question'][$i]['isSavedGame'] = false;
            }
            $questionData['question'][$i]['is_delete'] = false;
        }
/*
        if (!empty($playHistoryDB)){
            $playHistory = array(
                'playHistoryId' => $playHistoryDB[0]->getId(), 
                'playStartedAt' => $playHistoryDB[0]->getPlayStartedAt(), 
                'playEndedAt' => $playHistoryDB[0]->getPlayEndedAt(),
                'clearTime' => $playHistoryDB[0]->getClearTime(),
                'suspendedTime' => $playHistoryDB[0]->getSuspendedTime(),
                'gameStatus' => $playHistoryDB[0]->getGameStatus(),
                'playInfo' => $playHistoryDB[0]->getPlayInfo(), // Javascriptでは playData
                );
*/
        $questionData=json_encode($questionData);//jscon encode the array
        return new Response($questionData,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
    }
    public function uploadDataAction(){
        $logger = $this->get('logger');

        $request = $this->get('request');

        $data=$request->request->get('playInfo');
        $syncToken = $request->request->get('userToken');
        $status = $request->request->get("status");
        $isSavedGame = $request->request->get("isSavedGame");
        $questionId = (int)($request->request->get('questionId'));
		
        $users = $this->getDoctrine()
                ->getManager()
                ->getRepository('KanaheiGameBundle:User')->findBy(array('syncToken' =>$syncToken));
        $user = $users[0];

		$params = array(
			"data" => $data,
			"user" => $user,
			"status" => $status,
			"isSavedGame" => $isSavedGame,
			"questionId" => $questionId
		);

		// 処理の共通化
		$this->saveGameData($params);

        $responseData=json_encode(array("status" => "OK"));//json encode the array
        $logger->info("downloadAction: all done.");
        return new Response($responseData,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type

    /* 参考
    http://symfony2forum.org/threads/5-Using-Symfony2-jQuery-and-Ajax
    */
    }

     public function auIdLoginAction(){
        return $this->redirect($this->generateUrl('AndroidRegisterEntry'));
     }

	 public function registerEntryAction(){
        $form = $this->createFormBuilder()
         ->setMethod('POST')
         ->add('nickname', 'text')
         ->add('confirm', 'submit', array('label'=>'内容を確認'))
         ->getForm();
		
        $request = $this->get("request");
        $session = $request->getSession();
		$openId = $session->get("openId");
		$ua = $request->headers->get('User-Agent');

		//if( !strstr($ua, "Android") ){	
		if(true){					
			// リワード
			$cid = "6250";
			$ad  = "install";
			$uid = hash('sha256', $openId);
			$key = "8ccc6ee910d93df31a1e48b542724e5b";

			$to_digest = "$ad:$cid:$uid:$key";
			$digest = hash('sha256', $to_digest);

	        return $this->render('KanaheiGameBundle:Android:registerEntry.html.twig',
					array('form' => $form->createView(), 	
					'cid'=>$cid, 'ad' => $ad, 'uid' => $uid, 'digest' => $digest, 'reword' => true ) );

		} else {

	        return $this->render('KanaheiGameBundle:Android:registerEntry.html.twig',
					array('form' => $form->createView(), 'reword' => false) );
		}			

	 }
     public function registerConfirmAction(){
        $request = $this->get("request");

        $form = $this->createFormBuilder()
        ->setMethod('POST')
        ->add('nickname', 'hidden')
        ->add('confirm', 'submit')
        ->getForm();
        $form->bind($request);
        $nickname = $form->getData();

        return $this->render('KanaheiGameBundle:Android:registerConfirm.html.twig',array('nickname'=>$nickname, 'form' => $form->createView()));
     }
     public function registerCompleteAction(){
        $request = $this->get("request");
        $session = $request->getSession();

        $syncToken = $session->get("syncTokenPre");
		$openId = $session->get("openId");

		if(empty($syncToken)){
			return $this->redirect($this->generateUrl('Top'));
		}
		
        $form = $this->createFormBuilder()
        ->setMethod('GET')
        ->add('nickname', 'hidden')
        ->add('confirm', 'submit')
        ->getForm();
        $form->bind($request);
        //ユーザ登録処理, OpenID, syncToken, nicknameを登録する。
        $userData = $form->getData();
        $nickname = $userData["nickname"];
        $createdAt = new DateTime();
        $updatedAt = new DateTime();

        $user = new User();
        $user->setNickname($nickname);
        $user->setAuId($openId);
        $user->setSyncToken($syncToken);
        $user->setCreatedAt($createdAt->format("Y-m-d H:i:s"));
        $user->setUpdatedAt($updatedAt->format("Y-m-d H:i:s"));
        $user->setPassword("");
        $user->setTempPass("");
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

		 $log = new Log();
		 $log->setUserId($user->getId());
		 $log->setType("register");
		 $log->setName("nickname setting complete");
		 $log->setCreatedAt(date("Y-m-d H:i:s"));
		 $em->persist($log);
		 $em->flush();	 

        $session->remove("syncTokenPre");
        $session->set("syncToken", $syncToken);
        $session->set("id", $user->getId());
//        $session->set("auId", );

		return $this->render('KanaheiGameBundle:Android:registerComplete.html.twig',
				array('syncToken'=> $syncToken, 'nickname'=> $nickname, 'reword' => false));
		

     }
     public function copyrightAction(){
        $request = $this->get("request");
        $errorFlg = true;
        try{
            $questionId = $request->query->get("id");
            if(!empty($questionId)){
                $question = $this->getDoctrine()->getManager()->getRepository('KanaheiGameBundle:Question')->find($questionId);
                if(!empty($question)){
                    $url = $question->getCopyrightUrl();
                    if(!empty($url)){
                        //URLが存在する場合
                        $errorFlg = false;
                    }
                }
            }

        }catch(Exception $e){
            $logger = $this->get('logger');
            $logger->info("UrlNotFound AndroidController.copyrightAction:". $file);
            throw $this->createNotFoundException('お探しのページは見つかりませんでした。');
        }

        //URLが取得できなかった場合
        if($errorFlg == true)  throw $this->createNotFoundException('お探しのページは見つかりませんでした。');
        //URLが取得できた場合
        return $this->redirect($url);
     } 
     /*
     *
     * Android端末でのWebViewでのアクセス時のセッションをスタートする。
     * GET /sync/session/start?syncToken=xxxxx&redirect=xxxxxxx
     */
     public function sessionStartAction(){
            $request = $this->get('request');
            $session = $request->getSession();  

            $syncToken = $request->query->get("syncToken");
            $redirect = $request->query->get("redirect");
            //開発モード時,セッションを生成する。

            $users = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('KanaheiGameBundle:User')->findBy(array('syncToken' =>$syncToken));

            if( empty($users) ) {
                //ゲストユーザの場合は何もしない。   
            }else{
                $user = $users[0];
                $session->set('auId', $user->getAuId());
                $session->set('id',  $user->getId());
                $session->set('smartPassResult', true );
            }
            return $this->redirect($this->generateUrl($redirect));            
     }
     public function sessionFinishAction(){
        $session = $request->getSession();  
        $session->remove('id');
        $session->remove('nickname');
        $session->remove('syncToken');
        $session->remove('auId');
        $responseData=json_encode(array("status" => "OK"));//json encode the array
        return new Response($responseData,200,array('Content-Type'=>'application/json'));//make sure it has the correct content type
     }
	 
	 public function registDeviceIdAction(){
        $request = $this->get('request');
		$token = $request->request->get('token');
		$regid = $request->request->get('regId');
		$user = null;

        $em = $this->getDoctrine()->getManager();
		$users = $em->getRepository('KanaheiGameBundle:User')->findBy(array(
			'syncToken' => $token
		));
		
		$regists = $em->getRepository('KanaheiGameBundle:Regist')->findBy(
			array("code" => $regid)
		);
		
		$regist = null;
		if(empty($regists)){
			$regist = new Regist();
			$regist->setUserId(0);
			$regist->setCreatedAt(date("Y-m-d H:i:s"));
		} else {
			$regist = $regists[0];
		}
		$regist->setCode($regid);
		if(!empty($users)){
			$user = $users[0];
			$regist->setUserId($user->getId());
		}
		$em->persist($regist);
		$em->flush();

        $responseData=json_encode(array("status" => "OK"));
        return new Response($responseData,200,array('Content-Type'=>'application/json'));
	}
}