var	Time  = {
	_milliSeconds: null,

	milliSecondsToArray: function(milliSeconds){
		this._milliSeconds = milliSeconds;

		var mm = this._getMinuteString();
		var ss = this._getSecondString();
		var ms = this._getMillisecondString().slice(-2);

		return [mm,ss,ms];
	},
	milliSecondsToStringWithColon: function(milliSeconds){
		return this.milliSecondsToString(milliSeconds,":",":");
	},
	milliSecondsToString: function(milliSeconds,separator1,separator2){
		this._milliSeconds = milliSeconds;
		var array = this.milliSecondsToArray(milliSeconds);
		return array[0] + separator1 + array[1] + separator2 + array[2];
	},

	_padding:function (num,char,n){
		var val = String(num);
		for(; val.length < n; val+=char);
		return val;
	},
    _getMinuteString:function(){
		var time = this._milliSeconds;
		var target = Math.floor( (time / 1000) / 60 );
		return this._padding( target, "0", 2 );
	},
    _getSecondString:function(){
		var time = this._milliSeconds;
		var target = Math.floor((time / 1000) % 60);
		return this._padding( target, "0", 2 );
	},
    _getMillisecondString:function(){
		var time = this._milliSeconds;
		var target = Math.floor(time % 1000);
		return this._padding( target, "0", 4 );
    },
    _concatenateDigitStringsToTime:function(){

		var colon = ":";
		return (mm + colon + ss + colon + ms);
    }
};