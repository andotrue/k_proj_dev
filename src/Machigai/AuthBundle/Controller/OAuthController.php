<?php

namespace Machigai\AuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class OAuthController extends Controller {

	public function responseTokenAction() {
		
		// au ID OAuth連携用パラメータ
		$clientId = "AJlZDAAAAUK3x8nX";
		$clientSecret = "8A6a-TTacq1Hk7yACeR6w3YZCv_w-ykW";
		$scope = "";//"apass4web";
		
		//$redirectUrl = "https://" . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"] . "?method=redirect";
		$redirectUrl = $this->get('router')->generate('response_token', array('method' => 'redirect'), true);
		$authzReqUrl = "https://oa.connect.auone.jp/net/opi/hny_oauth_rt_net/cca" .
				"?ID=OpenAPIAcpt&response_type=code&client_id=" . $clientId .
				"&redirect_uri=" . $redirectUrl . "&scope=" . urlencode($scope);
		
		$tokenReqUrl = "https://oa.connect.auone.jp/net/opi/hny_oauth_rt_net/cca" .
				"?ID=OpenAPITokenCodeReq";
		$tokenParams = "grant_type=authorization_code&redirect_uri=" . $redirectUrl .
				"&client_id=" . $clientId . "&client_secret=" . $clientSecret;
		
		
		// セッション処理開始
		$request = $this->getRequest();
		$session = $request->getSession();
		$query = $request->query;

		// パラメータ定義
		$method = empty($GET["method"]) ? "get" : $query->get("method");
		$state = $session->get("state", null);  // return null if state doesn't exist.
		$code = null;
		$accessToken = null;
		$refreshToken = null;
		$refreshLimit = null;
		
		$SERVER["SCRIPT_NAME"] = $this->get('router')->generate('response_token');
		
		// methodパラメータ別に処理実施
		switch ($method) {
			// === アクセストークン未取得での接続時 ===
			case 'get':
				if (empty($state)) {
					
					// stateパラメータに固有乱数を設定し、連携URLに付与
					$state = md5(uniqid(rand(), TRUE));
					$session->set("state", $state);
					$authzReqUrl .= "&state=" . $state;
					// metaリフレッシュで認可要求
					$response_html = '<html><head><meta http-equiv="refresh" content="1" url="' . $authzReqUrl . '"></head><body>[AUTHZ_REQ] Please wait...</body></html>';
					return new Response($response_html);
					
				}
				break;
				
			// === 認可要求からの戻り処理(認可応答) ===
			case 'redirect':
				
				// セッション状態が一致しない場合
				$state_query = $query->get("state");

				if (!empty($state_query) && $state_query != $state) {
					$response_html = '<html><body>Unmatch[' . $query('state') .
							' <=> ' . $state . ']<br /><br /><a href="' .
							$_SERVER["SCRIPT_NAME"] . '">Retry</a></body></html>"';
					return new Response($response_html);
				}
				
				// サーバエラー時はメッセージ表示して終了
				$error_query = $query->get('error');
				$error_description_query = $query->get('error_description');
				if (!empty($error_query) || !empty($error_description_query)) {
					$error_message = $query->get('error') . '[' . 
							$query->get('error_description') . ']';
					$a_link = '<a href="' . $_SERVER["SCRIPT_NAME"] .
							'?method=retry">Retry</a>';
					return new Response("<html><body>" . $error_message .
							'<br /><br />' . $a_link . "</body></html>");
				}
				
				// サーバ発行したcodeパラメータ設定
				$code_query = $query->get('code');
				if (!empty($code_query)) {
					$code = $query->get("code"); //$code = $_GET['code'];
					// 連携パラメータにcode付与
					$tokenParams .= "&code=" . $code;
				}
				
				// 連携実施(アクセストークン取得要求/アクセストークン取得応答)
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $tokenReqUrl);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $tokenParams);
				list($header, $body) = explode("¥r¥n¥r¥n", curl_exec($ch));
				curl_close($ch);

				// サーバレスポンスをJSON変換
				$jobj = json_decode($body);
				// サーバエラー時はメッセージ表示して終了
				if (!empty($jobj->error) || !empty($jobj->error_description)) {
					print "<html><body>";
					$error_message = $jobj->error . '[' . $jobj->error_description . ']';
					$a_link = '<a href="' . $_SERVER["SCRIPT_NAME"] . '?method=retry">Retry</a>';
					return new Response("<html><body>" . $error_message . "<br /><br />" . $a_link . "</body></html>");
				}
				// 取得データをセッション/変数格納
				$accessToken = $jobj->access_token;
				$refreshToken = $jobj->refresh_token;
				$refreshLimit = $jobj->expires_in;
				break;
				
			// === リトライ時 ===
			case 'retry':
				$session->clear();
				header("Location: " . $_SERVER["SCRIPT_NAME"]);
				exit;
		}

		$pass_array = array('state' => $state, 'code' => $code, 
			'accessToken' => $accessToken, 'refreshToken' => $refreshToken,
			'refreshLimit' => $refreshLimit);
		
		return $this->render('MachigaiAuthBundle:OAuth:response_token.html.twig', $pass_array);
	}

}
