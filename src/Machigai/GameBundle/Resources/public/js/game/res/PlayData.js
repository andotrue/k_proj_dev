//ゲーム中のプレイ情報
//クリックされたポイントと経過時間（中断時間）に関する時刻情報をもつ。	
var PlayData = cc.Class.extend({
	_userId: null,
	_playHistoryId: null,
	_qcode: null,
	_level: null,
	_limitTime: null, // milliseconds
	_gameStatus: null,
	_isSaved: null,
	_touchData: [],
	_clockData: [],

	save:function(){
		cc.log("PlayData.save : isSaved() =" + this.isSaved());
		this._isSaved = true;
		return this.isSaved;
	},
	getUserId:function(){
		cc.log("PlayData.getUserId : " + this.getUserId());
		return this._userId;
	},
	getPlayHistoryId:function(){
		cc.log("PlayData.getPlayHistoryId : " + this.getPlayHistoryId());
		return this.getPlayHistoryId;
	},
	getQcode:function(){
		cc.log("PlayData.getQcode : " + this.getQcode());
		return this._qcode;
	},
	getLevel:function(){
		cc.log("PlayData.getLevel : " + this.getLevel());
		return this._level;
	},
	getGameStatus:function(){
		cc.log("PlayData.getGameStatus : " + this.getGameStatus());
		return this._gameStatus;
	},
	changeGameStatus:function(gameStatus){
		cc.log("PlayData.changeGameStatus : this._gameStatus , gameStatus = " + this._gameStatus() + ", " + gameStatus);
		var invalid = "PlayData.chachangeGameStatus: Invalid Change.";
		if ( gameStatus == 1 || gameStatus == 3)  throw invalid;
		if ( this._gameStatus == 2 && gameStatus == 4 )  throw invalid;
		if ( this._gameStatus == 4 && (gameStatus == 2 || gameStatus == 5) ) throw invalid;
		if ( this._gameStatus == 5 ) throw invalid;

		this._gameStatus = gameStatus;
		cc.log("PlayData.changeGameStatus : success. this._gameStatus is now " + this._gameStatus());

		return this.getGameStatus();
	},
	setTouchData:function(touchData){
		cc.log("PlayData.setTouchData(touchData) : data =  " + touchData.toString() );
		this._touchData = touchData;
	},
	setClockData:function(clockData){
		cc.log("PlayData.setClockData(clockData) : data =  " + clockData.toString() );
		this._clockData = clockData;
	},
	getTouchData:function(){
		cc.log("PlayData.getTouchData() : data =  " + this._touchData.toString() );
		return this._touchData;
	},
	getClockData:function(){
		cc.log("PlayData.getClockData() : data =  " + this._clockData.toString() );
		return this._clockData;
	},
	isSaved:function(){
		cc.log("PlayData.isSaved() : result is " + this._isSaved );
		return this._isSaved;
	},
	serializeData:function(){
		var hash = {
			userId: this._userId,
			playHistoryId: this._playHistoryId,
			qcode: this._qcode,
			level: this._level,
			touchData: this._touchData,
			clockData: this._clockData
		};
		cc.log("PlayData.serializeData() : return " + hash.toString() );
		return hash;
	},
	serializeDataToJSONString:function(){
		cc.log("PlayData.serializeDataToJSONString() : return " + this.serializeData.toString() );
		return this.serializeData.toString();
	},
	ctor:function(){
		cc.log("PlayData.ctor()");
	}
});

PlayData.FIRST_PLAY = 1;
PlayData.TRIED_BUT_FAILED = 2;
PlayData.FIRST_TRIAL_SUCCEEDED = 4;
PlayData.SECOND_TRIAL_SUCCEEDED = 5;

//新規にプレイを開始する場合
PlayData.create = function(qcode, level){
	cc.log("PlayData.create( " + qcode + ", " + level + " )" );
	var instance = new PlayData();
	instance._userId = null;
	instance._qcode = qcode;
	instance._level = level;
	instance._gameStatus = PlayData.FIRST_PLAY;
	instance._isSaved = false;
	instance._touchData = [];
	instance._clockData = [];
	cc.log("PlayData.create(): result = ( " +  ", " +	instance._qcode + ", " + instance._level + ", " +instance._gameStatus + ", "  + instance._isSaved + ", " + instance._touchData + ", " + instance._clockData+ " )" );

	return instance;
};
//ゲームデータのロード（中断データなど)
PlayData.loadFromJSON = function(json_data){
	cc.log("PlayData.loadFromJSON( " + json_data.toString() +" )");
	var instance = new PlayData();
	instance._userId = json_data["userId"];
	instance._playHistoryId = json_data["playHistoryId"];
	instance._qcode = json_data["qcode"];
	instance._level = json_data["level"];
	instance._limitTime = json_data["limitTime"];
	instance.setTouchData(json_data['touchData']);
	instance.setClockData(json_data['clockData']);
	cc.log("PlayData.loadFromJSON(): result = ( " + instance._userId + ", " +	instance._qcode + ", " + instance._level + ", " +instance._gameStatus + ", "  + instance._isSaved + ", " + instance._touchData + ", " + instance._clockData + " )" );

	return instance;
};

