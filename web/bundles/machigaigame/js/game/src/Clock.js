var Clock = cc.LayerGradient.extend({
    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;
        if (this._super()) {
			this.digitWidth = 30;
			this.digitHeight = 43;
			this.coronWidth = 20;
            this.initSelf();
            this.initDigits();

            this.setDigits("00:00:00");
        }
        return bRet;
    },
    initSelf:function(){

		this.setContentSize(cc.size(240,50));
        this.setStartColor(cc.c3b(255,0,0));
        this.setEndColor(cc.c3b(255,0,255));
        this.setStartOpacity(255);
        this.setEndOpacity(255);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        this.setBlendFunc(blend);

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
    setDigits:function(timeStr){
		for (var i = this.reservedDigits.length - 1; i >= 0; i--) {
			this.reservedDigits[i].removeFromParent();
		}
		var left = 0;
		for (var i = 0; i < timeStr.length ; i++) {
			var target_num = timeStr[i];

			if (target_num == ":"){
				target_num = "coron";
				width = this.coronWidth;
			}else{
				width = this.digitWidth;
			}
			left += width;
			target = cc.Sprite.create( gsDir + "number/game_number_" + target_num + ".png" );
			this.addChild(target);
			this.reservedDigits[i] = target;
			target.setPosition( left, this.digitHeight *0.6);
		}
    },

});