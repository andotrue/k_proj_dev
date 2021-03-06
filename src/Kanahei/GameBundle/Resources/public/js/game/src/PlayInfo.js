var PlayInfo = cc.Class.extend({
	QUESTION_ID: null,
	QCODE: null,
	TITLE: null,
	LEVEL: null,
	CLEAR_POINT: null,
	BONUS_POINT: null,
	TIME_LIMIT: null,
	FAIL_LIMIT: null,
	kanahei_POINT_DATA: null,
	kanahei_LIMIT: null,
	_data: null, //download Data
	_definition: null,
	_playData: null,
	_playDataJSON: null,
	_clock: null,
	_user: null,
	_isSucceed: false,
	_isPlayed: false,

	getUserID:function(){
		if(this.isUser()){
			return this._user['userId'];
		}else{
			return null;
		}
	},
	isGuest:function(){
		return !this.isUser();
	},
	isUser:function(){
		if(this._user["userId"] && this._user["userId"] != "0"){
			return true;
		}else{
			return false;
		}
	},
	isFirstTime:function(){
		if(this._user === null){
			return null;
		}
		return (this._user['firstTime'] === true);
	},
	isSucceed:function(){
		return this._isSucceed;
	},
	setSucceed:function(){
		this._isSucceed = true;
		this.clock.stopTimer();
		return this._isSucceed;
	},
	setFail:function(){
		this._isSucceed = false;
		this.clock.stopTimer();
		return this._isSucceed;
	},
	getCurrentPoint:function(){
		if(this._user !== null){
			return this._user['currentPoint'];
		}else{
			return null;
		}
	},
	getNewCurrentPoint:function(){
		if (this.isUser === true && this.isFirstTime() && this.isSucceed){
			cc.log("PlayInfo.currentPoint = " + this._user['currentPoint'] + this.CLEAR_POINT);
			return this._user['currentPoint'] + this.CLEAR_POINT;
		}else{
			return 0;
		}
	},
	setTouchData:function(cilckPoints){
		cc.log("PlayInfo.setTouchData(): reusult = " + this._playData.setTouchData(clickPoints) );
		this._playData.setTouchData(clickPoints);
	},
	setClockData:function(clockData){
		cc.log("PlayInfo.setClockData(): reusult = " + this._playData.setClockData(clockData) );
		this._playData.setClockData(clockData);
	},
	getTouchData:function(){
		cc.log("PlayInfo.getTouchData(): reusult = " + this._playData.getTouchData() );
		return this._playData.getTouchData();
	},
	getClockData:function(){
		cc.log("PlayInfo.getClockData(): reusult = " + this._playData.getClockData() );
		return this._playData.getClockData();
	},
	setClock:function(clock){
		this.clock = clock;
		clock._clockData = this._playData._clockData;
		clock.setPlayInfo(this);
	},
	getClearTime:function(){
		if(this.clock === null) throw "PlayInfo: No Clock set!";
		return this.clock.getClearTime();
	},
	getDefinition:function(){
		cc.log("PlayInfo.getDefinition(): reusult = " + this._definition);
		return this._definition;
	},
	getPlayDataTextEncodedByJSON:function(){
		cc.log("PlayInfo.getPlayDataTextEncodedByJSON(): result = " + this._playData.stringify() );
		return this._playData.stringify();
	},
	getPlayData:function(){
		cc.log("PlayInfo.getPlayData(): reusult = " + this._playData );
		return this._playData;
	},
	ctor:function(questionId){
		cc.log("PlayInfo.ctor(): questionId = " + questionId);
		this.QUESTION_ID = questionId;
		this.init();
	},
	init:function(){
		cc.log("PlayInfo.init()");
		this._downloadData();
		this._initDefinition();
		this._initPlayData();
	},
	_downloadData:function(){
		var xhttp=new XMLHttpRequest();
		xhttp.open("GET","/sync/game/" + this.QUESTION_ID + "/" +uid,false);
//		xhttp.setRequestHeader('X-CSRF-Token', csrf_token);
		xhttp.send("");
		var xmlDoc=xhttp.responseText;
		cc.log(xmlDoc);
		var data = JSON.parse(xmlDoc);
		if( data['error'] === true ){
			this._error_redirect_to("../select");
		}
		this._data = data;
		if( data && data.playHistory && data.playHistory.playInfo){
			var playInfo = JSON.parse(data.playHistory.playInfo);
			this._data.playHistory.playInfo = playInfo;
			this._data.playInfo = data.playHistory.playInfo;
		}
		this._user = this._data["user"];
		this._user["userId"] = uid;
		this._definition = this._data["question"];
		if(this._data.playHistory && this._data.playHistory.isSavedGame){
			this._playDataJSON = this._data.playHistory;
		}
		
		csrf_token = this._data["csrf_token"];

	},
	_sendPlayHistory:function(){
		//token, user_id, QUESTION_ID,  _playData,(	_touchData: [], _clockData: [],)
		if(userId === undefined || this._playData.length < 1 ) throw "data not sufficient.";

		array = [userId,startedAt, touchData,clockData];

		var xhttp=new XMLHttpRequest();
		xhttp.open("POST","/sync/playHistory/" + this.QUESTION_ID,false);
		xhttp.setRequestHeader('X-CSRF-Token', csrf_token);
		xhttp.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );

		xhttp.send("");

		return true;
	},

	//LocalStorage保存用
	_serializePlayHistory:function(){
		//TODO: implement here.
	},
	_savePlayHistoryToLocalStrorage:function(){
		//TODO: implement here.

	},
	_error_redirect_to:function(url){
		window.location = url;
		throw "CAN'T GET QUESTION!";
	},
	_downloadQuestionXMLFromserver:function(){
		cc.log("PlayInfo._downloadQuestionXMLFromserver()");
		var xhttp=new XMLHttpRequest();
		xhttp.open("GET","/game/download/" + this.LEVEL +"/" + this.QCODE + "/xml",false);
		xhttp.send("");
		return xhttp.responseXML;
	},
	_fetchKanaheiPointDataFromServer:function(){
		cc.log("PlayInfo._fetchKanaheiPointDataFromServer()");
		var xmlQuestionDoc = this._downloadQuestionXMLFromserver();
		var pointNodes = xmlQuestionDoc.getElementsByTagName('point');
		var points =[];
		for (var i = 0; i < pointNodes.length; i++){
			var x = pointNodes[i].getElementsByTagName('x')[0].textContent;
			var y = pointNodes[i].getElementsByTagName('y')[0].textContent;
			points.push( { x: x, y: y });
		}
		return points;
	},
	_initDefinition:function(){
		cc.log("PlayInfo._initDefinition()");
		if(this._definition === undefined || this._definition === null) throw "PlayInfo._initDefinition: no definition supplied!";
		var definition = this._definition;
		cc.log("PlayInfo.QUESTION_ID = " + definition['questionId']);
		this.QUESTION_ID =  definition['questionId'];
		cc.log("PlayInfo.QUESTION_NUMBER = " + definition['questionNumber']);
		this.QUESTION_NUMBER = definition['questionNumber'];
		cc.log("PlayInfo.QCODE = " + definition['qcode']);
		this.QCODE = definition['qcode'];
		this.TITLE = definition['questionTitle'];
		cc.log("PlayInfo.LEVEL = " + definition['level']);
		this.LEVEL = definition['level'];
		cc.log("PlayInfo.TIME_LIMIT = " + definition['timeLimit']);
		this.TIME_LIMIT = definition['timeLimit'];
		cc.log("PlayInfo.FAIL_LIMIT = " + definition['failLimit']);
		this.FAIL_LIMIT = definition['failLimit'];
		cc.log("PlayInfo.CLEAR_POINT = " + definition['clearPoint']);
		this.CLEAR_POINT = definition['clearPoint'];
		cc.log("PlayInfo.BONUS_POINT = " + definition['bonusPoint']);
		this.BONUS_POINT = definition['bonusPoint'];
		cc.log("PlayInfo.kanahei_POINT_DATA = " + this._fetchKanaheiPointDataFromServer());
		this.kanahei_POINT_DATA = this._fetchKanaheiPointDataFromServer();
		cc.log("PlayInfo.kanahei_LIMIT = " + this.kanahei_POINT_DATA.length);
		this.kanahei_LIMIT = this.kanahei_POINT_DATA.length;
	},
	_initPlayData:function(){
		cc.log("PlayInfo._initPlayData()");
		if(this._playDataJSON === undefined) throw "PlayInfo._initPlayData: no info supplied!";
		if(this._playDataJSON === null){
			this._playData = PlayData.create(this.QCODE, this.LEVEL);
		}else{
			this._playData = PlayData.loadFromJSON(this._playDataJSON);
		}
		
		// ゲームステータスを同期
		if( this._data && this._data.playHistory && this._data.playHistory.playInfo){
			this._playData._gameStatus = this._data.playHistory.gameStatus;
		}

	}
});