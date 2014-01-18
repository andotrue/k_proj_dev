var MyGameScene = cc.Scene.extend({
    playInfo: null,
    onEnter:function () {
        this._super();
        var self = this;
//        gScoreData.initData();

//        var spriteFrameCache = cc.SpriteFrameCache.getInstance();
//        spriteFrameCache.addSpriteFrames("res/baseResource.plist","res/baseResource.png");
        var paths = window.location.pathname.split("/");
        var questionId = paths[paths.length -1];
        cc.log("MyGameScene: questionId = " + questionId);

        this.playInfo = new PlayInfo(questionId);

        var baseLayer = new BaseLayer(self, this.playInfo);
        this.addChild(baseLayer,10);


        var popupLayer = new PopupLayer("PLAY",baseLayer);
        this.addChild(popupLayer,20);
        this.addChild(baseLayer,10);

//        gSharedEngine.setMusicVolume(1);
//        gSharedEngine.setEffectsVolume(1);
//        gSharedEngine.playMusic(MUSIC_BACKGROUND,true);
    },
    onExit:function(){
		cc.log("MyGameScene.onExit()");
		this._super();
    }
});