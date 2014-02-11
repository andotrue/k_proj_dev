<?php

namespace Machigai\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Machigai\GameBundle\Entity\PlayHistory;

class BaseController extends Controller
{
    /** DEBUGモード　*/
    public $MODE = "DEBUG";
    public $DEBUG = "DEBUG";

	public function getUser()
	{
        $session = $this->get('session');
        $id = $session->get('id');
        if(!empty($id)){
        	$user = $this->getDoctrine()
	        ->getRepository('MachigaiGameBundle:User')
	        ->find($id);
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

        $question = $this->getDoctrine()
                ->getManager()
                ->getRepository('MachigaiGameBundle:Question')->find($params["questionId"]);

        $playHistories = $this->getDoctrine()
                ->getManager()
                ->createQuery('SELECT p from MachigaiGameBundle:PlayHistory p
                                    where p.user = :user and p.question = :question')
                ->setParameters(array('user'=>$params["user"],'question'=>$question))
                ->getResult();


        if(empty($playHistories)){
//            $logger->info("uploadDataAction: playHistory is null.");
            $playHistory = new PlayHistory();
//            $playHistory->setCreatedAt(new DateTime());
//            $playHistory->setUpdatedAt();
            $playHistory->addQuestion($question);
            $playHistory->setPlayInfo($params["data"]);
            $playHistory->setUser($params["user"]);
            $playHistory->setGameStatus($params["status"]);
            $playHistory->setIsSavedGame($params["isSavedGame"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($playHistory);
            $this->applyRanking($playHistory);
            $em->flush();
//            $logger->info("uploadDataAction: playHistory is saved.");
        }else{
            $playHistory = $playHistories[0];
			$updatedAt = new \DateTime();
            $playHistory->setUpdatedAt($updatedAt->format("Y-m-d H:i:s"));
            $playHistory->setGameStatus($params["status"]);
            $playHistory->setPlayInfo($params["data"]);
            $playHistory->setIsSavedGame($params["isSavedGame"]);

            $em = $this->getDoctrine()->getManager();
            $playHistory->setPlayInfo($params["data"]);
            $em->persist($playHistory);
            $this->applyRanking($playHistory);
            $em->flush();
        }
	}

    /*
    *
    *   Rankingに登録処理を行う
    */
     public function applyRanking($playHistory){
        //TODO: Ranking登録処理。
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