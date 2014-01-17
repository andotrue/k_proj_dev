var PlayInfo = cc.Class.extend({
	QUESTION_ID: null,
	QCODE: null,
	TITLE: null,
	LIMIT_TIME: null,
	PERMITTED_OTETSUKI_COUNT: null,
	MACHIGAI_POINT_DATA: null,
	_definition: {},
	_playData: null,
	setClickPointsData:function(cilckPoints){
		this._playData.setClickPointsData(clickPoints);
	},
	setClockData:function(clockData){
		this._playData.setClockData(clockData);
	},
	getClickPointsData:function(){
		return this._playData.getClickPointsData();
	},
	getClockData:function(){
		return this._playData.getClockData();
	},
	getDefinition:function(){
		return this._definition;
	},
	getPlayDataTextEncodedByJSON:function(){
		return String(this._playData);
	},
	getPlayData:function(){
		return this._playData;
	},
	ctor:function(definition, data){
//		this._super();
		this.init(definition,data);
	},
	init:function(definition, data){
		cc.log("PlayInfo.onEnter");
		this._initDefinition(definition);
		this._initPlayData(data);
	},
	_initDefinition:function(definition){
		if(definition === undefined){
			this._definition = null;
			return false;
		}
		this._definition = definition;
		this.QUESTION_ID =  definition['QUESTION_ID'];
		this.QCODE = definition['QCODE'];
		this.TITLE = definition['TITLE'];
		this.LIMIT_TIME = definition['LIMIT_TIME'];
		this.PERMITTED_OTETSUKI_COUNT = definition['PERMITTED_OTETSUKI_COUNT'];
		this.MACHIGAI_POINT_DATA = definition['MACHIGAI_POINT_DATA'];
	},
	_initPlayData:function(data){
		if(data === undefined || data === null){
			this._playData = new PlayData();
			return false;
		}
		this._playData = data;
	},

	onEnter:function(){
		cc.log("PlayInfo.onEnter");
	},

	onExit:function(){
		cc.log("PlayInfo.onExit");
		this._playData = null;
	}
});