<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Entity\PlayHistory;
use Machigai\GameBundle\Entity\Ranking;
use Machigai\GameBundle\Entity\User;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
class BaseController extends Controller
{
    /** DEBUGモード　*/
    public $MODE = "DEBUG";
    public $DEBUG = "DEBUG";

	public function getUser()
	{
        $session = $this->get('session');
        $id = $session->get('id');

		$request = $this->get('request');
		$cookies = $request->cookies;

		// クッキーチェック
		if(empty($id) && $cookies->has('myCookie')){


			$syncToken = $cookies->get("myCookie");

        	$user = $this->getDoctrine()
				->getRepository('MachigaiGameBundle:User')
				->findBy(array("syncToken" => $syncToken));

			if(!empty($user)){
				$id = $user[0]->getId();
				$session->set("id", $id);
			}
		}

        if(!empty($id)){
        	$user = $this->getDoctrine()
	        ->getRepository('MachigaiGameBundle:User')
	        ->find($id);

            if(empty($user)) {
                $session->remove($id);
                return null;
            }
			if(!$cookies->has('myCookie')){
				$cookie = new Cookie('myCookie', $user->getSyncToken(), time() + 3600 * 24 * 30);
				$response = new Response();
				$response->headers->setCookie($cookie);
				$response->send();
			}

			return $user;
        }else{
        	//GUESTの場合NULLを返す
            $user = NULL;
            return $user;
        }
        return null;
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
                if($differences < 2592000 && $differences != 0){
                    $purchasedItems[] = $purchasedItem->getItem()->getId();
                }
            }
        }
        return $purchasedItems;
    }

	public function saveGameData($params){
        $logger = $this->get('logger');
        $logger->info('Android.saveGameData');
        $em = $this->getDoctrine()->getManager();

        $user = $params["user"];

        $question = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:Question')->find($params["questionId"]);

        $playHistories = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$params["user"],'question'=>$question))
                ->getResult();
        //TODO: クリアタイム計算
        $duration = 0;

        $data = json_decode($params["data"], true);
        $clockData = $data["clockData"];
        $touchData = $data["touchData"];
        $logger->info("Android.saveGameData: " . $data["clockData"]["0"]["resumed"]);

        foreach($clockData as $datum){
            //AndroidとWebAppでは時刻計算手法が違う。
            //Android: 整数（long型）
            //WebApp:  時刻形式
            $interrupted = null;
            $resumed = null;
            $interrupted  = (int)$datum['interrupted'];
            $resumed =  (int)$datum['resumed'];
            $duration += $interrupted - $resumed;
        }
        $logger->info("Android.saveGameData: duration = " . $duration);


        //questionのmachigaiLimitを計算//
        $level = $question->getLevel();
        $qcode = $question->getQcode();
        //xmlファイルパス生成
//        $filePath = $_SERVER . "/sync/game/file/" . $level . "/" . $qcode . "/xml";
        $fileName = sprintf("%05d", $qcode);
        $filePath = getcwd() . "/" . "../src/Machigai/GameBundle/Resources/questions/" . $level . "/" . $qcode . "/" . "MS" . $fileName  . ".xml";
        $logger->info("Android.saveGameData: xmlFilePath = " . $filePath);
        //xmlファイル取得
        $xmlString = file_get_contents($filePath);
        $logger->info("Android.saveGameData: xmlString = " . $xmlString);
        //xml->オブジェクト
        $xml = simplexml_load_string($xmlString);
        //xml->count
        $machigaiLimit = count($xml->point);
        $logger->info("Android.saveGameData: machigaiLimit = " . $machigaiLimit);

        //クリアかどうかの判別
        $isClear = false;
        $countResultOfMachigaiLimit = 0;
        foreach ($touchData as $datum) {
            if($datum["result"] != true){
                $logger->info(" result is false");
            }else{
                $logger->info(" result is true");
                $countResultOfMachigaiLimit +=1;
            }
        }

        if( $countResultOfMachigaiLimit == $machigaiLimit ){
            $isClear = true;
        }
        $logger->info(" \$isClear is $isClear");


        if(empty($playHistories)){
            $logger->info("Android.saveGameData: playHistory is null.");
            $playHistory = new PlayHistory();
//            $playHistory->setCreatedAt(new DateTime());
//            $playHistory->setUpdatedAt();
            $playHistory->addQuestion($question);
            $playHistory->setPlayInfo($params["data"]);
            $playHistory->setUser($params["user"]);
            $playHistory->setGameStatus($params["status"]);
            $playHistory->setIsSavedGame($params["isSavedGame"]);
            if($isClear == true){
                //初回挑戦でクリア：userにポイント付与
                $addPoint = $question->getClearPoint() + $question->getBonusPoint();
                $newCurrentPoint = $user->getCurrentPoint() + $addPoint;
                $user->setCurrentPoint($newCurrentPoint);
                $logger->info(" point is added to User. point = " . $newCurrentPoint);

                $playHistory->setClearTime($duration);
                $em->persist($user);
                //初回挑戦でクリアなので、ランキング計算
                $this->applyRanking($playHistory);

            }
            $em->persist($playHistory);
            $em->flush();
            $logger->info("Android.saveGameData: playHistory is saved.");
        }else{
            $logger->info("Android.saveGameData: playHistory exists.");
            $playHistory = $playHistories[0];
			$updatedAt = new \DateTime();
            $playHistory->setUpdatedAt($updatedAt->format("Y-m-d H:i:s"));
            $playHistory->setGameStatus($params["status"]);
            $playHistory->setPlayInfo($params["data"]);
            $playHistory->setIsSavedGame($params["isSavedGame"]);
            $playHistory->setPlayInfo($params["data"]);
            if($isClear == true){
                if($playHistory->getClearTime() == null){
                    //はじめて該当問題をクリアしたときにポイント付与
                    $addPoint = $question->getClearPoint();
                    $newCurrentPoint = $user->getCurrentPoint() + $addPoint;
                    $user->setCurrentPoint($newCurrentPoint);
                    $em->persist($user);
                    $logger->info(" point is added to User. point = " . $newCurrentPoint);
                };
                $playHistory->setClearTime($duration);
            }
            $em->persist($playHistory);
            $em->flush();
            $logger->info("Android.saveGameData: playHistory is saved.");
        }
	}

    /**
       Ranking登録処理
    */
    public function applyRanking($playHistory){
        $logger = $this->get('logger');
        $logger->info('Android.applyRanking');


        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $question = $playHistory->getQuestion();
        $user = $playHistory->getUser();

        $clearTime = $playHistory->getClearTime();
        $gameLevel = $question->getLevel();
        $bonusPoint = $question->getBonusPoint();
        $month = date('n');
        $year = date('Y');
        $logger->info('Android.applyRanking:before get rankings;');

        $rankings = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT r from MachigaiGameBundle:Ranking r
                                    where r.level = :gameLevel and r.year = :year and r.month = :month order by r.rank asc')
                ->setParameters(array('gameLevel'=>$gameLevel,'year'=>$year,'month'=>$month))
                ->getResult();
//        $logger->info('Android.applyRanking:after get rankings;' .get_class($rankings) ) ;
        // ランキング初登録 //
        if(empty($rankings)){
            $logger->info('Android.applyRanking: rankings are empty;');

            $newRank = new Ranking();
            $newRank->setUser($user);
            $newRank->setYear($year);
            $newRank->setMonth($month);
            $newRank->setLevel($gameLevel);
            $newRank->setRank(1);
            $newRank->setClearTime($clearTime);
//            $newRank->setClearPoint(1);
//            $newRank->setBonusPoint($bonusPoint);
            $newRank->setCreatedAt(date("Y-m-d H:i:s"));
            $newRank->setUpdatedAt(date("Y-m-d H:i:s"));
            $em->persist($newRank);
            $em->flush();
            return;

        }else{
           $logger->info('Android.applyRanking: rankings exists;');
           $isRegistered = false;
           $playHistory = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$user,'question'=>$question))
                ->getResult();

            $newRankings = array();
            $logger->info('Android.applyRanking: before foreach');
            foreach ($rankings as $rank) {

                $logger->info('Android.applyRanking: before if:');
                if($clearTime < $rank->getClearTime()){
                    $isRegistered =true;
                    $logger->info('Android.applyRanking: after if');

                    $rankId = $rank->getId();
                    $newRank = null;
                    $logger->info('Android.applyRanking: find newRanks');
                    $newRanks = $em->getRepository('MachigaiGameBundle:Ranking')->findBy(array('user'=>$user, 'level' => $gameLevel, 'year' => $year, 'month' => $month));

                    $logger->info('Android.applyRanking: newRanks empty;');

                    if(empty($newRanks)){
                        $newRank = new Ranking();
                    }else{
                        $newRank = $newRanks[0];
                    }
                    $newRank->setUser($user);
                    $newRank->setYear($year);
                    $newRank->setMonth($month);
                    $newRank->setLevel($gameLevel);
                    $newRank->setRank($rank->getRank());
                    $newRank->setClearTime($clearTime);
//                    $newRank->setBonusPoint($bonusPoint);
                    $newRank->setCreatedAt(date("Y-m-d H:i:s"));
                    $newRank->setUpdatedAt(date("Y-m-d H:i:s"));
                    $em->persist($newRank);

                    for($i = $rankId; $i < count($rankings); $i++ ){
                        if($i == 10 ){
                            //削除
                            $afterRank = $rankings[$i];
                            $em->remove($afterRank);
                        }else{
                            $afterRank = $rankings[$i];
                            $afterRank->setRank($i+1);
                            $afterRank->setUpdatedAt(date("Y-m-d H:i:s"));
                            $em->persist($afterRank);
                        }
                    }

                    $em->flush();
                    break;
                }
                $newRankings[] = $rank;
            }
			$newRanks = $em->getRepository('MachigaiGameBundle:Ranking')->
					findBy(array('user'=>$user, 'level' => $gameLevel,
						'year' => $year, 'month' => $month));

			if ($isRegistered == false && empty($newRanks) &&  count($rankings) < 10){
                    $newRank = new Ranking();
                    $newRank->setUser($user);
                    $newRank->setYear($year);
                    $newRank->setMonth($month);
                    $newRank->setLevel($gameLevel);
                    $newRank->setRank(count($rankings) + 1);
                    $newRank->setClearTime($clearTime);
//                  $newRank->setBonusPoint($bonusPoint);
                    $newRank->setCreatedAt(date("Y-m-d H:i:s"));
                    $newRank->setUpdatedAt(date("Y-m-d H:i:s"));
                    //$em->persist($newRank);
                    //$em->flush();
                    $newRankings[] = $newRank;
            }
            $sort = array();
            foreach ($newRankings as $key => $value) {
                $sort[$key] = $value->getClearTime();
            }
            array_multisort($sort,SORT_ASC,$newRankings);
            for ($i=0; $i<count($newRankings) ; $i++) {
                $rank = $i+1;
                $newRankings[$i]->setRank($rank);
                $em->persist($newRankings[$i]);
                $em->flush();
            }
        }
    }
/*
    //TODO: AndroidController.php の auIdActionと同一アクションなので、一方に集約する。
    public function auIdAction()
    {
        $connectTo = "connect.auone.jp";
        $logger = $this->get('logger');
        $logger->info('inf auIdAction');

        $realm = "https://machigai.puzzle-m.ne.jp/";
        $formId = "test";
        $returnToUrl = "https://machigai.puzzle-m.ne.jp/auIdAssociation";

        $associationDirPath = "/tmp";
        $preDealPath  = "/net/id/hny_rt_net/cca?ID=auOneOpenIDOther";
        $connectTo = "https://connect.auone.jp";
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
                return $this->redirect("Error");
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

    //TODO: AndroidController.php の auIdAssociationActionと同一アクションなので、一方に集約する。
    public function auIdAssociationAction(){
        $logger = $this->get('logger');
        $logger->info('inf auIdAssociationAction');
        $associationDirPath = "/tmp";
        $return_to = "/auIdComplete";
        // RP(Consumer)のインスタンス生成までは認証リクエスト時と同じ
        $store = new Auth_OpenID_FileStore($associationDirPath);
        $consumer =& new Auth_OpenID_Consumer($store);
        // Return_to をセットして、認証を完了(OP-Identifier による認証時の再 Discovery 等)
        $response = $consumer->complete($return_to);

        if ($response->status == Auth_OpenID_CANCEL) { // Cancel メッセージが帰ってきた場合の処理
        } else if ($response->status == Auth_OpenID_FAILURE) { // エラーが帰ってきた場合の処理
            $this->redirect("Error");
        } else if ($response->status == Auth_OpenID_SUCCESS) { // 認証成功。以下の方法でユーザの OpenID を取得
            $openid = $response->getDisplayIdentifier();
            //ユーザを探す。
            $users = $this->getDoctrine()
                ->getRepository('MachigaiGameBundle:User')
                ->findBy(array("auId"=>$syncToken));
            if(empty($users)){
                //ユーザのニックネーム登録ページヘ。
                $this->redirect('AndroidRegisterEntry',array("openId" => $openId));
            }else{
                //ユーザのログイン完了
                $user = $users[0];
                $syncToken = $user->getSyncToken();
                $nickname = $user->getNickname();

                //セッションを登録。
                return $this->render('MachigaiGameBundle:Android:registerComplete.html.twig', array('syncToken'=> $syncToken, 'nickname'=> $nickname));
//                $this->redirect('Top',array());
            }
        }
    }
*/
}