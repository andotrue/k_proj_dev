var PopupLayer = cc.Layer.extend({
    state: null,
    clock: null,
    baseLayer: null,
    MIDDLE_Y: 704,
    playInfo: null,
    ctor:function (type,baseLayer) {
        cc.log("PopupLayer.ctor");
        this._super();
        this.baseLayer = baseLayer;
        this.clock = baseLayer.clock;
        this.playInfo = baseLayer.playInfo;
        this.init(type);
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
                case 'GAMEOVER_SUCCESS':
                    this.popupGameoverSuccess();
                    break;
                case 'GAMEOVER_FAIL':
                    this.popupGameoverFail();
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
        this.setPosition(360,this.MIDDLE_Y );
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
        cc.log("Popup.onTouchBegan");
        return true;
    },

    /**
     * callback when a touch event moved
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchMoved:function (touch, event) {
        cc.log("Popup.onTouchMoved");
        return true;
    },

    /**
     * callback when a touch event finished
     * @param {cc.Touch} touch
     * @param {event} event
     */
    onTouchEnded:function (touch, event) {
        cc.log("Popup.onTouchEnded");
        switch (this.state){
            case "PLAY":
                cc.log("Popup.onTouchEnded: PLAY. now removing self. start the timer.");

                var baseLayer = this.baseLayer;
                baseLayer.clock.startTimer();
                this.removeFromParent();
                break;
            case "GAMEOVER_SUCCESS":
                this.gameoverSuccess();
                break;
            case "GAMEOVER_FAIL":
                this.gameoverFail();
                break;
        }
        return true;
    },

    popupLoading:function () {
        this.state = "LOADING";
        var PopupQuestiondownload = cc.Sprite.create( gsDir + "popup/questiondownload.png" );
        PopupQuestiondownload.setPosition(360,this.MIDDLE_Y );
        this.addChild(PopupQuestiondownload);
    },

    popupMiss:function () {
        this.state = "MISS";
        var label = cc.Sprite.create( gsDir + "label/miss.png" );
        this.addChild(label);
        popup.setPosition(360,this.MIDDLE_Y );
    },

    popupClear:function () {
        this.state = "CLEAR";
        var label = cc.Sprite.create( gsDir + "label/clear.png" );
        this.addChild(label);
        popup.setPosition(360,this.MIDDLE_Y );
    },

    popupPlay:function () {
        this.state = "PLAY";
        var ss = ["popup/gamestart.png", "popup/gamestart_first.png", "popup/gamestart_guest.png","popup/gamestart_notfirst.png"];
        var i =this.game_status = 1;
        var popup = cc.Sprite.create( gsDir + ss[i] );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y);
    },
    popupHint:function () {
//        cc.unregisterTouchDelegate(this);
        this.state = "HINT";

        var popup = cc.Sprite.create( gsDir + "popup/hint.png" );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );

        var yes = this.createYesButton(360,620);
        var no = this.createNoButton(360,535);
        var menu = cc.Menu.create([yes,no]);
        menu.setPosition(0,0);
        this.addChild(menu);
    },
    hint:function () {
        cc.log("PopupLayer.hint");
    },
    popupSave:function () {
        this.state = "SAVE";
        path = gsDir + "popup/save.png";
//        path = gsDir + this.is_guest ? "popup/save.png" : "popup/save_guest.png";
        var popup = cc.Sprite.create(path);
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );

        var yes = this.createYesButton(360,749);
        var no = this.createNoButton(360,679);
        var menu = cc.Menu.create([yes,no]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    save:function () {
        cc.log("PopupLayer.save");
        cc.log(document.location);
        window.location="../select";

    },
    popupGiveup:function () {
        this.state = "GIVEUP";
        var popup = cc.Sprite.create( gsDir + "popup/giveup.png" );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );

        var yes = this.createYesButton(360,710);
        var no = this.createNoButton(360,605);
        var menu = cc.Menu.create([yes,no]);
        menu.setPosition(0,0);
        this.addChild(menu);

    },
    giveup:function () {
        cc.log("PopupLayer.giveup");
        cc.log(document.location);

        window.location="../select";
    },
    popupGameoverSuccess:function(){
        this.state = "GAMEOVER_SUCCESS";
        var popup = cc.Sprite.create( gsDir + "label/clear.png" );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );
    },
    gameoverSuccess:function(){
        var nextScene = new ResultScene(this.playInfo);
        cc.Director.getInstance().replaceScene(cc.TransitionFade.create(0.5, nextScene, cc.c3b(255,255,255)));
        this.removeFromParent();
        this._parent.removeFromParent();
    },
    popupGameoverFail:function(){
        this.state = "GAMEOVER_FAIL";
        var popup = cc.Sprite.create( gsDir + "label/miss.png" );
        this.addChild(popup);
        popup.setPosition(360,this.MIDDLE_Y );
    },
    gameoverFail:function(){
        var nextScene = new ResultScene(this.playInfo);
        cc.Director.getInstance().replaceScene(cc.TransitionFade.create(0.5, nextScene, cc.c3b(255,255,255)));
        this.removeFromParent();
        this._parent.removeFromParent();
    },
    menuCallBack:function (sender) {
        cc.log('PopupLayer.menuCallBack');
        switch(sender.name){
            case 'YES':
                cc.log('YES');
                this.removeFromParent();
                switch(this.state){
                    case 'HINT':
                        this.hint();
                        break;
                    case 'SAVE':
                        this.save();
                        break;
                    case 'GIVEUP':
                        this.giveup();
                        break;
                }
                break;
            case 'NO':
                cc.log('NO');
                this.removeFromParent();
                break;
            default:
                cc.log('default');
        }
    },
    createYesButton:function (x,y) {
        var yes = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_yes.png",
            bd+"res/game_scene/button/button_yes_off.png",
            this.menuCallBack.bind(this)
        );
        yes.setPosition(x, y);
        yes.name = "YES";
        return yes;
    },
    createNoButton:function (x,y) {
        var no = cc.MenuItemImage.create(
            bd+"res/game_scene/button/button_no.png",
            bd+"res/game_scene/button/button_no_off.png",
            this.menuCallBack.bind(this)
        );
        no.setPosition(x, y);
        no.name = "NO";
        return no;
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
        this.clock = null;
        this._super();
    }
});