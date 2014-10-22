<?php

namespace Machigai\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use ErrorException;


class OAuthController extends Controller {

	public function responseTokenAction() {

		set_error_handler (function ($errno, $errstr, $errfile, $errline) {
			// 例外に変換
			throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
			return false;
		}, E_ALL ^ E_NOTICE);
		
		try {
			
			$logger = $this->get('logger');

			// au ID OAuth連携用パラメータ
			$clientId = "AJlZDAAAAUK3x8nX";
			$clientSecret = "8A6a-TTacq1Hk7yACeR6w3YZCv_w-ykW";
			$scope = "apass4web";
			$responseType = "code";

			$redirectUrl = "https://machigai.puzzle-m.net/top/auth/oauth/response_token?method=redirect";
			//$redirectUrl = "https://machigai.puzzle-m.net/top";
			//$redirectUrl = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"] . "?method=redirect";
			//$redirectUrl = $this->get('router')->generate('response_token', array('method' => 'redirect'), true);
			$authzReqUrl = "https://oa.connect.auone.jp/net/opi/hny_oauth_rt_net/cca" .
					"?ID=OpenAPIAcpt&response_type=" . $responseType . "&client_id=" . $clientId .
					"&redirect_uri=" . urlencode($redirectUrl) . "&scope=" . urlencode($scope);

			$tokenReqUrl = "https://oa.connect.auone.jp/net/opi/hny_oauth_rt_net/cca" .
					"?ID=OpenAPITokenCodeReq";
			$tokenParams = "grant_type=authorization_code&redirect_uri=" . $redirectUrl .
					"&client_id=" . $clientId . "&client_secret=" . $clientSecret;


			// セッション処理開始
			$request = $this->getRequest();
			$session = $request->getSession();
			$query = $request->query;

			// パラメータ定義
			$method = empty($_GET["method"]) ? "get" : $query->get("method");
			$state = $session->get("state", null);  // return null if state doesn't exist.
			$code = $query->get("code", null);
			$accessToken = null;
			$refreshToken = null;
			$refreshLimit = null;

			$cookies = $request->cookies;

			$_SERVER["SCRIPT_NAME"] = $this->get('router')->generate('response_token');

			// methodパラメータ別に処理実施
			switch ($method) {
				// === アクセストークン未取得での接続時 ===
				case 'get':
					// stateパラメータに固有乱数を設定し、連携URLに付与
					$state = md5(uniqid(rand(), TRUE));
					$session->set("state", $state);
					$authzReqUrl .= "&state=" . $state;
					// metaリフレッシュで認可要求

					return $this->redirect($authzReqUrl);

					//$response_html = '<html><head><meta http-equiv="refresh" content="1" url="' . $authzReqUrl . '"></head><body>[AUTHZ_REQ] Please wait...</body></html>';
					//return new Response($response_html);			
					break;				
				// === 認可要求からの戻り処理(認可応答) ===
				case 'redirect':
					$response = new Response();

					if(!$cookies->has("smartToken")){

						// セッション状態が一致しない場合
						$state_query = $query->get("state");

						if (!empty($state_query) && $state_query != $state) {
							//リトライ
							$session->set("state", null);
							return $this->redirect($authzReqUrl);
						}

						// サーバエラー時はメッセージ表示して終了
						$error_query = $query->get('error');
						$error_description_query = $query->get('error_description');
						if (!empty($error_query) || !empty($error_description_query)) {
							//サーバーエラー
							return $this->redirect($this->generateUrl("Error"));
						}

						// サーバ発行したcodeパラメータ設定
						$code_query = $query->get('code');
						$logger->info("code_query = $code_query");
						if (!empty($code_query)) {
							$code = $query->get("code"); //$code = $_GET['code'];
							// 連携パラメータにcode付与
							$tokenParams .= "&code=" . $code;
						}

						// 連携実施(アクセストークン取得要求/アクセストークン取得応答)
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $tokenReqUrl);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_HEADER, true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $tokenParams);
						list($header, $body) = explode("\r\n\r\n", curl_exec($ch));
						curl_close($ch);

						// サーバレスポンスをJSON変換
						$jobj = json_decode($body);
						
						if($jobj == NULL){
							return $this->redirect($this->generateUrl('Error'));
						}
						
						// サーバエラー時はメッセージ表示して終了
						if (!empty($jobj->error) || !empty($jobj->error_description)) {
							// サーバーエラー
							return $this->redirect($this->generateUrl("Error"));
						}
						// 取得データをセッション/変数格納
						$accessToken = $jobj->access_token;
						$refreshToken = $jobj->refresh_token;
						$refreshLimit = $jobj->expires_in;

						$cookie = new Cookie("smartToken", $accessToken, time() + (int)$refreshLimit );
						$response->headers->setCookie($cookie);

					} else {
						$accessToken = $cookies->get("smartToken");
					}

					//認証状態を問い合わせ
					$smartPathReqUrl = "https://auth.au-market.com/pass/AuthSpUser";
					$data = array( 'ver' => '1.0' );
					$headers = array(
						"Authorization: Bearer " .$accessToken,
						"x-sr-id :5403",
					);

					$options = array('http' => array(
						'protocol_version' => '1.1',
						'method' => 'POST',
						'content' => http_build_query( $data ),
						'header' => implode("\r\n", $headers),
					));
					$contents = file_get_contents($smartPathReqUrl, false, stream_context_create($options));

					$smartPassResponse = json_decode($contents);

					if($smartPassResponse == NULL){
						return $this->redirect($this->generateUrl('Error'));
					}
					
					if($smartPassResponse->status == "error"){
						//認証エラー
						return $this->redirect($this->generateUrl("Error"));
					}elseif( $smartPassResponse->status == "success"){
						if($smartPassResponse->aspuser == "true"){

							$cookie = new Cookie('smartContract', "true", time() + 3600 * 24);
							$response->headers->setCookie($cookie);
							$response->send();

              if($session->get("puzzlelp_access") == "true"){
                $session->set("puzzlelp_access", null);
  							return $this->redirect($this->generateUrl('puzzlelp'));
              }
              
							//認証OK
							return $this->redirect($this->generateUrl('Top'));

						}else{	
							//認証NG	
							$response->send();
							return $this->redirect("http://pass.auone.jp/gate/?ru=https%3A%2F%2Fmachigai.puzzle-m.net%2Ftop");
						}

					}else{
						//通信エラー
						//TODO: 通信エラー
						$response->send();
						return $this->redirect($this->generateUrl('Error'));
					}
					break;
			}

			return $this->redirect('http://auone.jp');
	
		} catch(ErrorException $e) {
			
			return $this->redirect($this->generateUrl('Error'));
		}
		
	}

}
