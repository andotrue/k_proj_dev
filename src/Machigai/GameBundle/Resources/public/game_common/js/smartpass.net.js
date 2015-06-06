
smartpass.global.addPackage("net");

(function() {
	
	var loadXML = smartpass.base.loadXML;
	var EventDispatcher = smartpass.base.EventDispatcher;
	var getRequest = smartpass.util.getRequest;
	
	
	/*******************************************************************
	 * URLの定義
	 ******************************************************************/
	 
	var URL = {
		
		//アクセストークン取得要求(PHP経由)
		REQUEST_TOKEN_URL : "/auth/oauth.php",
		
		//auIDログイン処理(PHP経由)
		REQUEST_AUID_URL : "/auth/auid.php",
		
		//auIDログアウト処理(PHP経由)
		LOGOUT_AUID_URL : "/auth/logout.php",
		
		GET_NICKNAME : "/api/get/nickname.php",
		
		REGIST_NICKNAME : "/api/post/nickname.php"
	};
	
	var COOKIE = {
		TOKEN : "nep_token",
		OPEN_ID : "nep_openid"
	};
	
	
	/*******************************************************************
	 * OAuth認証クラス
	 ******************************************************************/
	this._OAuthManager  = function() {
		
		//auIDログインフラグ
		this.auIDLogined = false;
		
		//OAuthログインフラグ
		this.oauthLogined = false;
		
		//ログインチェック終了フラグ
		this.completeCheck = false;
	};
	
	extend(this._OAuthManager, EventDispatcher);
	
	/*
	 * ログイン状態のチェック
	 */
	this._OAuthManager.prototype.checkOAuth = function() {
		
		//console.log("smartpass.global.isStub() " + smartpass.global.isStub());
		
		//スタブモード(認証なし)
		if (smartpass.global.isStub()) {
			this.oauthLogined = true;
			this.auIDLogined = true;
			return;
		}
		
		this.checkCookie();
	};
	
	/*
	 * クッキーのチェック
	 */
	this._OAuthManager.prototype.checkCookie = function() {
		
		//console.log("checkCookie");
		
		var token = $.cookie(COOKIE.TOKEN);
		var openId = $.cookie(COOKIE.OPEN_ID);
		
		//console.log("token = " + token);
		//console.log("openId = " + openId);
		
		var scope = this;
		
		if (token != "" && token != null && token != undefined) {
			//OAuth認証済
			
			if (openId != "" && openId != null && openId != undefined) {
				//au IDログイン済
				//console.log("au IDログイン済");
				this.auIDLogined = true;
			} else {
				//console.log("au ID未ログイン");
				//au ID未ログイン
				this.auIDLogined = false;
			}
			
			this.oauthLogined = true;
			//チェック終了したので画面を表示
			//this.dispatchEvent({type:"success"});
			
		} else {
			scope.request();
			window.setTimeout(function() {
				//OAuth認証していないから認証へ
				//scope.request();
			}, 5000);
		}
	};
	
	/*
	 * 認可要求の処理
	 */
	this._OAuthManager.prototype.request = function() {
		
		location.href = URL.REQUEST_TOKEN_URL;
	};
	
	/*
	 * アクセストークン取得(PHP経由)
	 */
	this._OAuthManager.prototype.getAccessToken = function(code) {
		
		var scope = this;
		
		//ajaxでPHP経由でトークンを取得
		$.get(URL.REQUEST_TOKEN_URL, { code : code }, function(token) {
			
				trace("Data Loaded: " + token);
				
				if (token) {
					//OAuth認証の成功
					scope.checkCookie();
					
				} else {
					
					//エラー画面を表示
					this.dispatchEvent({type:"fault"});
				}
		});
	};
	
	/*
	 * auIDログイン処理
	 * ボタン押下時
	 */
	this._OAuthManager.prototype.auIDLogin = function() {
		
		location.href = URL.REQUEST_AUID_URL;
	};
	
	/*
	 * auIDログアウト処理
	 * ボタン押下時
	 */
	this._OAuthManager.prototype.auIDLogout = function() {
		
		location.href = URL.LOGOUT_AUID_URL;
	};
	
	this.OAuthManager = new this._OAuthManager();
	
	
	/*******************************************************************
	 * マイページAPIクラス
	 ******************************************************************/
	this._MypageAPI  = function() {
		
	};
	
	extend(this._MypageAPI, EventDispatcher);
	
	this._MypageAPI.prototype.getName = function() {
		
		var scope = this;
		
		$.get(URL.GET_NICKNAME, { 
			site: 1, 
			token: $.cookie(COOKIE.OPEN_ID) 
		}, function(data){
			scope.dispatchEvent({type:"getName", args:{ data : data }});
		});
	};
	
	this._MypageAPI.prototype.saveName = function(value) {
		
		var scope = this;
		
		$.ajax({
			type: 'POST',
			url: URL.REGIST_NICKNAME,
			data: {
				site: 1, 
				token: $.cookie(COOKIE.OPEN_ID),
				nickname:value
			},
			success: function(data) {
				scope.dispatchEvent({type:"registName", args:{ data : data }});
			}
		});
	};
	
	this.MypageAPI = new this._MypageAPI();
	
}).apply(smartpass.net);


/*
 * ログインチェック(全画面共通処理)
 */
//var OAuthManager = smartpass.net.OAuthManager;
//OAuthManager.checkOAuth();


