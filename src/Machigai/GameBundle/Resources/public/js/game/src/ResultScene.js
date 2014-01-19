var ResultScene = cc.Scene.extend({
    onEnter:function (playInfo) {
        cc.log("ResultScene.ctor");
        this._super();

//        this.playData = playInfo.getPlayData();
        this.playData = null;
//        this.userId = this.playData.getUserId(); TODO:getUserId が変。
        this.userId = null;
        if (this.userId === null){
			this.isGuest = true;
		}else{
			this.isGuest = false;
		}

        this.acquiredPoint =300; // playInfo.CLEAR_POINT; //とりあえず対処、ボーナスポイント分の修正必要。
        var clockData =  null; //this.playData.getClockData();
        // this.clearTime = ;
        this.clearTime = 500000; //とりあえず対処、修正必要。	
        this.currentPoint = 11110; //とりあえず対処、修正必要。	

        var resultLayer = new ResultLayer(this.isGuest, this.clearTime, this.acquiredPoint, this.currentPoint);
        this.addChild(resultLayer,0);

    },
});

