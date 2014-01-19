var ResultScene = cc.Scene.extend({
    ctor:function(playInfo){
        cc.log("ResultScene.onEnter");
        this._super();
//        this.playData = playInfo.getPlayData();
        this.playInfo = playInfo;
//        this.userId = this.playData.getUserId(); TODO:getUserId が変。
        this.userId = playInfo.getUserId;
        if (playInfo.isGuest()){
            this.isGuest = true;
            this.currentPoint =null;
            this.acquiredPoint = null;
        }else{
            this.isGuest = false;
            this.currentPoint = playInfo.getNewCurrentPoint();
            if( playInfo.isSucceed() === true){
                this.acquiredPoint =playInfo.CLEAR_POINT;
            }else{
                this.acquiredPoint = 0;
            }
        }

         // playInfo.CLEAR_POINT; //とりあえず対処、ボーナスポイント分の修正必要。
//        this.currentPoint = playInfo.getCurrentPoint(); //とりあえず対処、修正必要。  
        this.clearTime = playInfo.getClearTime();
        if(this.userId !== null){
        }

    },
    onEnter:function () {
        cc.log("ResultScene.onEnter");
        this._super();

        var resultLayer = new ResultLayer(this.playInfo, this.isGuest, this.clearTime, this.acquiredPoint, this.currentPoint);
        this.addChild(resultLayer,0);

    },
});

