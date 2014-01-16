var PopupLayer = cc.Layer.extend({
    state: null,
    ctor:function () {
        cc.log("PopupLayer.ctor");
        this._super();
        this.init();
    },
    init:function (type) {
        cc.log("PopupLayer.init");
        var bRet = false;
        if (this._super()) {
            cc.log("loging");
//            this.setTouchEnabled(true);
//            this.setTouchPriority(100);
            cc.log("setTouch");

            switch(type){
                case 'GIVEUP':
                    this.popupGiveup();
                    break;
                case 'SAVE':
                    this.popupSave();
                    break;
                case 'HINT':
                    this.popupHint();
                    break;
                case 'PLAY':
                    this.popupPlay();
                    break;
                default:
            }

            bRet = true;
        }
        return bRet;
    },

    initSelf:function(){

/*        this.setContentSize(cc.size(720,1280));
        this.setStartColor(cc.c3b(0,128,0));
        this.setEndColor(cc.c3b(0,0,128));
        this.setStartOpacity(64);
        this.setEndOpacity(64);
        var blend =  new cc.BlendFunc();
        blend.src = cc.GL_SRC_ALPHA;
        blend.dst = cc.GL_ONE_MINUS_SRC_ALPHA;
        this.setBlendFunc(blend);
*/
        this.setPosition(360,640);
    },


    onUpdate:function (delta){
        cc.log("PopupLayer.onUpdate");
    },
   /**
     * default implements are used to call script callback if exist<br/>
     * you must override these touch functions if you wish to utilize them
     * @param {cc.Touch} touch
     * @param {event} event
     * @return {Boolean}
     */
    onTouchBegan:function (touch, event) {
        cc.log("onTouchBegan event should be handled.");
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("onTouchMoved event should be handled.");
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("onTouchEnded event should be handled.");
        if (this.state =="LOADING")
            this.removeFromParent();
        return true;
    },

    popupLoading:function () {
        this.state = "LOADING";
        var PopupQuestiondownload = cc.Sprite.create( gsDir + "popup/questiondownload.png" );
        PopupQuestiondownload.setPosition(360,640);
        this.addChild(PopupQuestiondownload);
    },

    popupMiss:function () {
        this.state = "MISS";
        var label = cc.Sprite.create( gsDir + "label/miss.png" );
        this.addChild(label);
        popup.setPosition(360,640);
    },

    popupClear:function () {
        this.state = "CLEAR";
        var label = cc.Sprite.create( gsDir + "label/clear.png" );
        this.addChild(label);
        popup.setPosition(360,640);
    },

    popupPlay:function () {
        this.state = "PLAY";
        var ss = ["popup/gamestart.png", "popup/gamestart_first.png", "popup/gamestart_guest.png","popup/gamestart_notfirst.png"];
        var i =this.game_status = 1;
        var popup = cc.Sprite.create( gsDir + ss[i] );
        this.addChild(popup);
        popup.setPosition(360,540);
    },
    popupHint:function () {
//        cc.unregisterTouchDelegate(this);
        this.state = "HINT";

        var popup = cc.Sprite.create( gsDir + "popup/hint.png" );
        this.addChild(popup);
        popup.setPosition(360,640);

        var yes = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_yes.png",
            bd+"res/game_scene/button/button_yes_off.png",
            this.menuCallBack.bind(this)
        );
        yes.setPosition(360, 550);
        yes.name = "YES";

        var no = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_no.png",
            bd+"res/game_scene/button/button_no_off.png",
            this.menuCallBack.bind(this)
        );
        no.setPosition(360, 470);
        no.name = "NO";

        var menu = cc.Menu.create([yes,no]);
        menu.setPosition(0,0);
        this.addChild(menu);
    },
    popupSave:function () {
        this.state = "SAVE";
        path = gsDir + "popup/save.png";
//        path = gsDir + this.is_guest ? "popup/save.png" : "popup/save_guest.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,640);
    },
    popupGiveup:function () {
        this.state = "GIVEUP";
        var popup = cc.Sprite.create( gsDir + "popup/giveup.png" );
        this.addChild(popup);
        popup.setPosition(360,640);

    },
    menuCallBack:function (sender) {
        cc.log('PopupLayer.menuCallBack');
        switch(sender.name){
            case 'YES':
                cc.log('YES');
                this.removeFromParent();
                break;
            case 'NO':
                cc.log('NO');
                this.removeFromParent();
                break;
            default:
                cc.log('default');
        }
    },
    onEnter:function () {
        cc.log("PopupLayer.onEnter");
       if(sys.platform == "browser")
            cc.registerTargetedDelegate(1, true, this);
        else
            cc.registerTargettedDelegate(1,true,this);
        this._super();
    },
    onExit:function () {
        cc.log("PopupLayer.onExit");
        cc.unregisterTouchDelegate(this);
        this._super();
    }
});