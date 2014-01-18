var IllustFrame = cc.Layer.extend({
    FRAME_WIDTH: 607,
    FRAME_HEIGHT: 387,
    FRAME_X: 58,
    FRAME_Y1: 873,
    FRAME_Y2: 476,
	image_file_path: null,
	rect: null,
	index: null,
	illust: null,
	friend: null,
    offsetX: null,
    offsetY: null,

	setFriend:function(friend){
		this.friend = friend;
	},
	calledUpdate:function(dx,dy,touched){
		cc.log("IllustFrame.update()");
        if( this.illust !== undefined){
          this.illust.removeFromParent();
        }
        var rect = this.rect;
        //TODO: clipAreaを定義

        this.illust = cc.Sprite.create(this.image_file_path /*, this.makeRect(index) */ );
        this.illust.setAnchorPoint(0.5,0.5);
        this.addChild( this.illust);

//        cc.log("this.currentScale, posX, posY = " + this.currentScale + ", " + posX + ", " + posY);
        this.illust.setPosition(dx , dy);
        this.scaleIllust(index);

	},
    update:function(dx,dy, touched ){
		cc.log("IllustFrame.update()");
        if( this.illust !== undefined){
          this.illust.removeFromParent();
        }
        var rect;
        this.offsetX += dx,
        this.offsetY += dy,


        this.illust = cc.Sprite.create(this.image_file_path /*, this.makeRect(index) */ );
        this.illust.setAnchorPoint(0.5,0.5);
        this.addChild( this.illust);

//        cc.log("this.currentScale, posX, posY = " + this.currentScale + ", " + posX + ", " + posY);
        this.illust.setPosition(dx , dy);
        this.scaleIllust(index);
//        this.friend.calledUpdate(dx,dy,touched);
    },
    updateScale:function(scale){
        this.illust.setScale(scale);
    },
    getCenterPoint:function(index){
      var direction =  (1.5 - index) * 2.0;
      var dx = FRAME_WIDTH/ 2.0;
      var dy = FRAME_HEIGHT / 2.0;
//      cc.log("getCenterPoint: direction = " + direction);
//      cc.log("getCenterPoint: dx, dy = " + dx + ", " + dy);
      return cc.p(dx, direction * dy);
    },

    getCenterPointToWorld:function(index){
      var point = this.getCenterPoint();
      return cc.p(this.getPosition().x + point.x, this.getPosition().y + point.y);
    },

    ctor:function (image_file_path, rect, index ) {
		cc.log("IllustFrame.ctor");
        this._super();
//        var _image_file_path = "/app_dev.php/game/download/1/105/xml";
        this.init(image_file_path, rect, index );
    },

    init:function (image_file_path, rect, index ) {
		cc.log("IllustFrame.init: image_file_path, rect, index,  = " + image_file_path + ", " + rect + ", " + index);
        var bRet = false;
        if (this._super()) {
			this.setAnchorPoint(0, 0);
			this.setPosition(0,0);
            this.offsetX = 0;
            this.offsetY = 0;
            this.image_file_path = image_file_path;
            this.index = index;
            var dis = this;



            var image = new Image();
            image.src = image_file_path;
            image.onload = function(){
                var _this = dis;   
                _this.setImage(this);
            };


          bRet = true;
        }
        return bRet;
    },
    setImage:function(sender){
        cc.log("this.width = " + sender.width );
        this.originalWidth = sender.width;

        var scale = 0.5;

//        var rect3 = this.getRectForClipArea(0, 0, sender.width, sender.height, scale);
        var rect3 =  cc.rect( 0 , 0, sender.width, sender.height);
        var rect3 = this.getRectForClipArea(0,0, sender.width, sender.height, scale);

        var illust = cc.Sprite.create(this.image_file_path, rect3);
//            cc.log("illust.size = ( " + illust.getContentSize().getWidth );
//            cc.log("illust.size = ( " + illust.getTextureRect().width );
        var rect1 = cc.rect(this.FRAME_X,this.FRAME_Y1,this.FRAME_WIDTH,this.FRAME_HEIGHT);
        var rect2 = cc.rect(this.FRAME_X,this.FRAME_Y2,this.FRAME_WIDTH,this.FRAME_HEIGHT);

        this.addChild(illust);

        switch(this.index){
            case 1:
                this.setPosition(this.FRAME_X,this.FRAME_Y1);
                this.rect = rect1;
                break;
            case 2:
                this.setPosition(this.FRAME_X,this.FRAME_Y2);
                this.rect = rect2;
        }

        var cx = this.FRAME_WIDTH  / 2;
        var cy = this.FRAME_HEIGHT / 2;
//            illust.setTextureRect(rect3);
        illust.setPosition(cx,cy);
        illust.setScale(scale);

        this.illust = illust;
    },


    //スプライトをカットするための領域を取得
    getRectForClipArea:function(offsetX, offsetY,  orgWidth, orgHeight, scale){
        if ( this.FRAME_WIDTH < scale * originalWidth || this.FRAME_HEIGHT < scale * originalHeight ){
            var cw = this.FRAME_WIDTH / scale ;
            var ch = this.FRAME_HEIGHT / scale;

            var cx = offsetX / scale;
            var cy = offsetY / scale;
            cc.log("cw, ch, cx, cy = ( " + cw + ", " + ch + ", " + cx + ", " + cy + ")");
        }else{
        }

    },
    getScale:function(){
        return this.illust.getScale();
    }
});
