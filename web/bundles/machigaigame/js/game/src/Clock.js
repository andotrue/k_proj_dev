var Clock = cc.Layer.extend({
	_INITIAL_TIME_MS: 5000000,
	_currentTime: 0,
	_digitWidth: 30,
	_digitHeight: 43,
	_coronWidth: 20,
    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
            this.initSelf();
            this.initDigits();

            this.setDigits(this._INITIAL_TIME_MS);
        }
        return bRet;
    },
    initSelf:function(){
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

    initDigits:function(){
		this.reservedDigits = [];
		this.digits = {};
		for (var i = 0; i <= 9; i++) {
			this.digits[i] = cc.Sprite.create( gsDir + "number/game_number_" + i + ".png" );
		}
		var coron = cc.Sprite.create( gsDir + "number/game_number_coron.png" );
		this.digits[':'] = coron;
    },
    getCurrentTime:function(){
		return this._currentTime;
	},

	_padding:function (vale,char,n){
		var val = toString(vale);
		for(; val.length < n; val+=char);
		return val;
	},
    _getMinuteString:function(){
		var time = this.getCurrentTime();
		var target = Math.floor( (time / 1000) / 60 );
		cc.log("Clock._getMinuteString: str = " + this._padding( target, "0", 2 ));
		return this._padding( target, "0", 2 );
	},
    _getSecondString:function(){
		var time = this.getCurrentTime();
		var target = (time / 1000) % 60;
		cc.log("Clock._getSecondString: str = " + this._padding( target, "0", 2 ));
		return this._padding( target, "0", 2 );
	},
    _getMillisecondString:function(){
		var time = this.getCurrentTime();
		var target = time % 1000;
		cc.log("Clock._getMillisecondString: str = " + this._padding( target, "0", 4 ));
		return this._padding( target, "0", 4 );
    },
    _concatenateDigitStringsToTime:function(){
		var mm = this._getMinuteString();
		var ss = this._getSecondString();
		var ms = this._getMillisecondString();
		var colon = ":";
		cc.log("Clock._getMinuteString: str = " + mm + colon + ss + colon + ms);
		return mm + colon + ss + colon + ms;
    },
    setDigits:function(ms){
		this.setDigitsByMilliSeconds(ms);
    },
    setDigitsByMilliSeconds:function(ms){
		var timeString = this._concatenateDigitStringsToTime();
		this.setDigitsByStr(timeString);
    },
    setDigitsByStr:function(timeStr){
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
			target = cc.Sprite.create( gsDir + "number/game_number_" + target_num + ".png" );
			this.addChild(target);
			this.reservedDigits[i] = target;
			cc.log("Clock.setDigitsByStr: str = " + timeStr, + ", left = " + left);
			target.setPosition( left, this._digiHeight *0.6);
		}
    },

});