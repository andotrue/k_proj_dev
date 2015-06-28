var Clock = cc.Layer.extend({
	TIME_LIMIT_RATIO: 1000,
	Y: 1300,
	X: 10,
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
	_timer: null,
	updateClock:function(){
		if (that.getCurrentDuration() <=0){
			that._status = that._FINISHED;
			that.parent.gameoverFail();
			clearInterval(that._timer);
		}else{
			that.updateDigits();
		}
	},
	startTimer:function(){
		that.resumeTimer();
		that._startTime = that._clockData[that._clockData.length - 1 ]['resumed'];
		that._timer = setInterval(that.updateClock, 233);
	},
	resumeTimer:function(){
		that._clockData.push({ 'resumed': new Date() });
		that._status = that._PLAYING;
	},
	interruptTimer:function(){
		that._clockData[ that._clockData.length -1 ]['interrupted'] = new Date();
		that._status = that._INTERRUPTED;
	},
	stopTimer:function(){
		that.interruptTimer();
		that._status = that._FINISHED;
		that._finishTime = that._clockData[that._clockData.length - 1 ]['interrupted'];
	},
	getPassedDuration:function(){
		var duration = 0;
		for (var i = that._clockData.length - 1; i >= 0; i--) {
			if( ( i == that._clockData.length - 1 ) && that._clockData[i]['interrupted'] === undefined ){
				duration += new Date() - that._clockData[i]['resumed'];
			}else{
				duration += that._clockData[i]['interrupted'] - that._clockData[i]['resumed'];
			}
		}
		return duration;
	},
	getCurrentDuration:function(){
		that._currentDuration = that._initial_time_ms - that.getPassedDuration();
		return that._currentDuration;
	},
    ctor:function (parent) {
//		if(playInfo === undefined) throw("Clock.ctor: playInfo is undefined!! ");
		that = this;
        that._super();
        that.parent = parent;
        that._playInfo = parent.playInfo;
        that.init();
    },
    init:function () {
        var bRet = false;
        if (that._super()) {
            that._initSelf();
            that._initDigits();
            that._initTimes();
        }
        return bRet;
    },
    _initSelf:function(){
/*		
		that.setContentSize(cc.size(240,50));
        that.setStartColor(cc.c3b(255,0,0));
        that.setEndColor(cc.c3b(255,0,255));
        that.setStartOpacity(255);
        that.setEndOpacity(255);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        that.setBlendFunc(blend);
*/
        that.setPosition(that.X,that.Y);
    },
    _initTimes:function(){
//		cc.log("Clock._initTimes");
		var playInfo = that._playInfo;
		that._status = that._LOADING;
//		cc.log("Clock._initTime: _initial_time_ms = " + playInfo.TIME_LIMIT * that.TIME_LIMIT_RATIO);
		that._initial_time_ms = playInfo.TIME_LIMIT * that.TIME_LIMIT_RATIO ;
		if(playInfo.playData !== undefined){
			that._clockData = playInfo.playData;
		}else{
			that._clockData = [];
		}
        that.updateDigits();
    },

    _initDigits:function(){
		that.reservedDigits = [];
		that.digits = {};
		for (var i = 0; i <= 9; i++) {
			that.digits[i] = cc.Sprite.create( gsDir + "number/game_number_" + i + ".png" );
		}
		var coron = cc.Sprite.create( gsDir + "number/game_number_coron.png" );
		that.digits[':'] = coron;
    },
	_padding:function (num,char,n){
		var val = String(num);
		for(; val.length < n; val+=char);
		return val;
	},
    _getMinuteString:function(){
		var time = that.getCurrentDuration();
		var target = Math.floor( (time / 1000) / 60 );
//		cc.log("Clock._getMinuteString: str = " + that._padding( target, "0", 2 ));
		return that._padding( target, "0", 2 );
	},
    _getSecondString:function(){
		var time = that.getCurrentDuration();
		var target = Math.floor((time / 1000) % 60);
//		cc.log("Clock._getSecondString: str = " + that._padding( target, "0", 2 ));
		return that._padding( target, "0", 2 );
	},
    _getMillisecondString:function(){
		var time = that.getCurrentDuration();
		var target = Math.floor(time % 1000);
//		cc.log("Clock._getMillisecondString: str = " + that._padding( target, "0", 4 ));
		return that._padding( target, "0", 4 );
    },
    _concatenateDigitStringsToTime:function(){
		var mm = that._getMinuteString();
		var ss = that._getSecondString();
		var ms = that._getMillisecondString().slice(2);
		var colon = ":";
//		cc.log("Clock._concatenateDigitStringsToTime: str = " + mm + colon + ss + colon + ms);
		return (mm + colon + ss + colon + ms);
    },
    updateDigits:function(){
		that.updateDigitsByMilliSeconds();
    },
    updateDigitsByMilliSeconds:function(){
		var timeString = that._concatenateDigitStringsToTime();
		that.updateDigitsByStr(timeString);
    },
    updateDigitsByStr:function(timeStr){
//		cc.log("Clock.updateDigitsByStr: str = " + timeStr );
		for (var i = that.reservedDigits.length - 1; i >= 0; i--) {
			that.reservedDigits[i].removeFromParent();
		}
		var left = 0;
		for (var i = 0; i < timeStr.length ; i++) {
			var target_num = timeStr[i];

			if (target_num == ":"){
				target_num = "coron";
				width = that._coronWidth;
			}else{
				width = that._digitWidth;
			}
			left += width;
			target = cc.Sprite.create( gsDir + "number/game_number_" + target_num + ".png" );
			that.addChild(target);
			that.reservedDigits[i] = target;
			target.setPosition( left, that._digitHeight *0.6);
		}
    },

});
Clock.RESUMED_KEY = 'resumed';
Clock.INTERRUPTED_KEY = 'interrupted';



