//
//
//

var Clock = cc.Layer.extend({
	TIME_LIMIT_RATIO: 1000,
	_initial_time_ms: 0,
	_PLAY_INFO_TIME_DATA_NAME: "interrupts",
	_PLAY_INFO_LIMIT_TIME_NAME: "TIME_LIMIT",
	_currentDuration: 0, // milliseconds (duration, Date - Dateの形式)
	_digitWidth: 30,
	_digitHeight: 43,
	_coronWidth: 20,
	_startTime: null,
	_clockData: [], //時刻データ
	_clearTime: null, // milliseconds (duration, Date - Dateの形式)
	_finishTime: null,
	_status: null,	//タイマー（ゲームのステータスコード）
	_LOADING: 0,
	_PLAYING: 1,
	_INTERRUPTED: 2,
	_FINISHED: 3,
	_playInfo: null,

	updateClock:function(){

	},
	startTimer:function(){
		this.resumeTimer();
		this._startTime = this._clockData[this._clockData.length - 1 ]['resumed'];
	},
	resumeTimer:function(){
		this._clockData.push({ 'resumed': new Date() });
		this._status = this._PLAYING;
	},
	interruptTimer:function(){
		this._clockData[ this._clockData.length -1 ]['interrupted'] = new Date();
		this._status = this._INTERRUPTED;
	},
	stopTimer:function(){
		this.interruptTimer();
		this._status = this._FINISHED;
		this._finishTime = this._clockData[this._clockData.length - 1 ]['interrupted'];
	},
	getPassedDuration:function(){
		var duration = 0;
		for (var i = this._clockData.length - 1; i >= 0; i--) {
			if( ( i == this._clockData.length - 1 ) && this._clockData[i]['interrupted'] === undefined ){
				duration += new Date() - this._clockData[i]['resumed'];
			}else{
				duration += this._clockData[i]['interrupted'] - this._clockData[i]['resumed'];
			}
		}
		return duration;
	},
	getCurrentDuration:function(){
		this._currentDuration = this._initial_time_ms - this.getPassedDuration();
		return this._currentDuration;
	},
    ctor:function (playInfo) {
		if(playInfo === undefined) throw("Clock.ctor: playInfo is undefined!! ");
        this._super();
        this._playInfo = playInfo;
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
            this._initSelf();
            this._initDigits();
            this._initTimes();
        }
        return bRet;
    },
    _initSelf:function(){
/*		
		this.setContentSize(cc.size(240,50));
        this.setStartColor(cc.c3b(255,0,0));
        this.setEndColor(cc.c3b(255,0,255));
        this.setStartOpacity(255);
        this.setEndOpacity(255);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        this.setBlendFunc(blend);
*/
        this.setPosition(30,1187);
    },
    _initTimes:function(){
		cc.log("Clock._initTimes");
		var playInfo = this._playInfo;
		this._status = this._LOADING;
		cc.log("Clock._initTime: _initial_time_ms = " + playInfo.TIME_LIMIT * this.TIME_LIMIT_RATIO);
		this._initial_time_ms = playInfo.TIME_LIMIT * this.TIME_LIMIT_RATIO ;
		if(playInfo.playData !== undefined){
			this._clockData = playInfo.playData;
		}else{
			this._clockData = [];
		}
        this.updateDigits();
    },

    _initDigits:function(){
		this.reservedDigits = [];
		this.digits = {};
		for (var i = 0; i <= 9; i++) {
			this.digits[i] = cc.Sprite.create( gsDir + "number/game_number_" + i + ".png" );
		}
		var coron = cc.Sprite.create( gsDir + "number/game_number_coron.png" );
		this.digits[':'] = coron;
    },
	_padding:function (num,char,n){
		var val = String(num);
		for(; val.length < n; val+=char);
		return val;
	},
    _getMinuteString:function(){
		var time = this.getCurrentDuration();
		var target = Math.floor( (time / 1000) / 60 );
		cc.log("Clock._getMinuteString: str = " + this._padding( target, "0", 2 ));
		return this._padding( target, "0", 2 );
	},
    _getSecondString:function(){
		var time = this.getCurrentDuration();
		var target = Math.floor((time / 1000) % 60);
		cc.log("Clock._getSecondString: str = " + this._padding( target, "0", 2 ));
		return this._padding( target, "0", 2 );
	},
    _getMillisecondString:function(){
		var time = this.getCurrentDuration();
		var target = Math.floor(time % 1000);
		cc.log("Clock._getMillisecondString: str = " + this._padding( target, "0", 4 ));
		return this._padding( target, "0", 4 );
    },
    _concatenateDigitStringsToTime:function(){
		var mm = this._getMinuteString();
		var ss = this._getSecondString();
		var ms = this._getMillisecondString().slice(-2);
		var colon = ":";
		cc.log("Clock._concatenateDigitStringsToTime: str = " + mm + colon + ss + colon + ms);
		return (mm + colon + ss + colon + ms);
    },
    updateDigits:function(){
		this.updateDigitsByMilliSeconds();
    },
    updateDigitsByMilliSeconds:function(){
		var timeString = this._concatenateDigitStringsToTime();
		this.updateDigitsByStr(timeString);
    },
    updateDigitsByStr:function(timeStr){
		for (var i = this.reservedDigits.length - 1; i >= 0; i--) {
			this.reservedDigits[i].removeFromParent();
		}
		var left = 0;
		for (var i = 0; i < timeStr.length ; i++) {
			var target_num = timeStr[i];

			if (target_num == ":"){
				target_num = "coron";
				width = this._coronWidth;
			}else{
				width = this._digitWidth;
			}
			left += width;
			cc.log("Clock.updateDigitsByStr: width = " + width);
			target = cc.Sprite.create( gsDir + "number/game_number_" + target_num + ".png" );
			this.addChild(target);
			this.reservedDigits[i] = target;
			cc.log("Clock.updateDigitsByStr: str = " + timeStr + ", left = " + left);
			target.setPosition( left, this._digitHeight *0.6);
		}
    },

});

Clock.RESUMED_KEY = 'resumed';
Clock.INTERRUPTED_KEY = 'interrupted';
