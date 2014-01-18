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
	_timer: null,
	updateClock:function(){
		if (self.getCurrentDuration() <=0){
			self._status = self._FINISHED;
			self.parent.gameoverFail();
			clearInterval(self._timer);
		}else{
			self.updateDigits();
		}
	},
	startTimer:function(){
		self.resumeTimer();
		self._startTime = self._clockData[self._clockData.length - 1 ]['resumed'];
		self._timer = setInterval(self.updateClock, 233);
	},
	resumeTimer:function(){
		self._clockData.push({ 'resumed': new Date() });
		self._status = self._PLAYING;
	},
	interruptTimer:function(){
		self._clockData[ self._clockData.length -1 ]['interrupted'] = new Date();
		self._status = self._INTERRUPTED;
	},
	stopTimer:function(){
		self.interruptTimer();
		self._status = self._FINISHED;
		self._finishTime = self._clockData[self._clockData.length - 1 ]['interrupted'];
	},
	getPassedDuration:function(){
		var duration = 0;
		for (var i = self._clockData.length - 1; i >= 0; i--) {
			if( ( i == self._clockData.length - 1 ) && self._clockData[i]['interrupted'] === undefined ){
				duration += new Date() - self._clockData[i]['resumed'];
			}else{
				duration += self._clockData[i]['interrupted'] - self._clockData[i]['resumed'];
			}
		}
		return duration;
	},
	getCurrentDuration:function(){
		self._currentDuration = self._initial_time_ms - self.getPassedDuration();
		return self._currentDuration;
	},
    ctor:function (parent) {
//		if(playInfo === undefined) throw("Clock.ctor: playInfo is undefined!! ");
		self = this;
        self._super();
        self.parent = parent;
        self._playInfo = parent.playInfo;
        self.init();
    },
    init:function () {
        var bRet = false;
        if (self._super()) {
            self._initSelf();
            self._initDigits();
            self._initTimes();
        }
        return bRet;
    },
    _initSelf:function(){
/*		
		self.setContentSize(cc.size(240,50));
        self.setStartColor(cc.c3b(255,0,0));
        self.setEndColor(cc.c3b(255,0,255));
        self.setStartOpacity(255);
        self.setEndOpacity(255);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        self.setBlendFunc(blend);
*/
        self.setPosition(30,1187);
    },
    _initTimes:function(){
//		cc.log("Clock._initTimes");
		var playInfo = self._playInfo;
		self._status = self._LOADING;
//		cc.log("Clock._initTime: _initial_time_ms = " + playInfo.TIME_LIMIT * self.TIME_LIMIT_RATIO);
		self._initial_time_ms = playInfo.TIME_LIMIT * self.TIME_LIMIT_RATIO ;
		if(playInfo.playData !== undefined){
			self._clockData = playInfo.playData;
		}else{
			self._clockData = [];
		}
        self.updateDigits();
    },

    _initDigits:function(){
		self.reservedDigits = [];
		self.digits = {};
		for (var i = 0; i <= 9; i++) {
			self.digits[i] = cc.Sprite.create( gsDir + "number/game_number_" + i + ".png" );
		}
		var coron = cc.Sprite.create( gsDir + "number/game_number_coron.png" );
		self.digits[':'] = coron;
    },
	_padding:function (num,char,n){
		var val = String(num);
		for(; val.length < n; val+=char);
		return val;
	},
    _getMinuteString:function(){
		var time = self.getCurrentDuration();
		var target = Math.floor( (time / 1000) / 60 );
//		cc.log("Clock._getMinuteString: str = " + self._padding( target, "0", 2 ));
		return self._padding( target, "0", 2 );
	},
    _getSecondString:function(){
		var time = self.getCurrentDuration();
		var target = Math.floor((time / 1000) % 60);
//		cc.log("Clock._getSecondString: str = " + self._padding( target, "0", 2 ));
		return self._padding( target, "0", 2 );
	},
    _getMillisecondString:function(){
		var time = self.getCurrentDuration();
		var target = Math.floor(time % 1000);
//		cc.log("Clock._getMillisecondString: str = " + self._padding( target, "0", 4 ));
		return self._padding( target, "0", 4 );
    },
    _concatenateDigitStringsToTime:function(){
		var mm = self._getMinuteString();
		var ss = self._getSecondString();
		var ms = self._getMillisecondString().slice(2);
		var colon = ":";
//		cc.log("Clock._concatenateDigitStringsToTime: str = " + mm + colon + ss + colon + ms);
		return (mm + colon + ss + colon + ms);
    },
    updateDigits:function(){
		self.updateDigitsByMilliSeconds();
    },
    updateDigitsByMilliSeconds:function(){
		var timeString = self._concatenateDigitStringsToTime();
		self.updateDigitsByStr(timeString);
    },
    updateDigitsByStr:function(timeStr){
//		cc.log("Clock.updateDigitsByStr: str = " + timeStr );
		for (var i = self.reservedDigits.length - 1; i >= 0; i--) {
			self.reservedDigits[i].removeFromParent();
		}
		var left = 0;
		for (var i = 0; i < timeStr.length ; i++) {
			var target_num = timeStr[i];

			if (target_num == ":"){
				target_num = "coron";
				width = self._coronWidth;
			}else{
				width = self._digitWidth;
			}
			left += width;
			target = cc.Sprite.create( gsDir + "number/game_number_" + target_num + ".png" );
			self.addChild(target);
			self.reservedDigits[i] = target;
			target.setPosition( left, self._digitHeight *0.6);
		}
    },

});
Clock.RESUMED_KEY = 'resumed';
Clock.INTERRUPTED_KEY = 'interrupted';



