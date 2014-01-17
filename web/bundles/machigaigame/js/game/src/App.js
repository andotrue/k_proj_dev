var MyGameScene = cc.Scene.extend({
    onEnter:function () {
        this._super();
        var self = this;
//        gScoreData.initData();

//        var spriteFrameCache = cc.SpriteFrameCache.getInstance();
//        spriteFrameCache.addSpriteFrames("res/baseResource.plist","res/baseResource.png");

        var baseLayer = new BaseLayer(self);
        this.addChild(baseLayer,10);


        var popupLayer = new PopupLayer("PLAY");
        this.addChild(popupLayer,20);


//        gSharedEngine.setMusicVolume(1);
//        gSharedEngine.setEffectsVolume(1);
//        gSharedEngine.playMusic(MUSIC_BACKGROUND,true);
    },
    onExit:function(){
		cc.log("MyGameScene.onExit()");
		this._super();
    }
});