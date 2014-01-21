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

		/*
		 * dx,dy は画像中の移動量
		 * offsetX,Y は今の画像の左上のX,Yを表す
		 */
		this.dx = dx;
		this.dy = dy;

		this.offsetY += Math.round(dy / this.scale);
		this.offsetX -= Math.round(dx / this.scale);
		
		// 移動量の上限を制御
		if( this.offsetX > this.originalWidth){
			this.offsetX = this.originalWidth;
		} else if( this.offsetX < 0 ){
			this.offsetX = 0;
		}
		
		if(this.offsetY > this.originalHeight){
			this.offsetY = this.originalHeight;
		} else if( this.offsetY < 0 ){
			this.offsetY = 0;
		}
		
		cc.log("offsetX,offsetY = " + this.offsetX + "," + this.offsetY);
		
		this.setImage();

	},
    updateScale:function(scale){
        this.illust.setScale(scale);
    },
    getCenterPoint:function(index){
      var direction =  (1.5 - index) * 2.0;
      var dx = this.FRAME_WIDTH / 2.0;
      var dy = this.FRAME_HEIGHT / 2.0;
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
			this.scale = 1;
			
            var dis = this;

            var image = new Image();
            image.src = image_file_path;
            image.onload = function(){
                dis.setImage(this);
            };

	        bRet = true;
        }
        return bRet;
    },
    setImage:function(sender){
		
		// 二回目以降は不要
		if( this.originalWidth == undefined &&
			this.originalHeight == undefined &&
			sender )
		{
	        this.originalWidth = sender.width;
			this.originalHeight = sender.height;
			
			/*
				ベーススケールの計算
				ベーススケールとは、画像を読みこんだ際、スケールが1に対して
				読み込んだ値をあらかじめ一定数で縮小/拡大するための数値
				これがある事によりスライダーからの割合を素直に受け取れる
			*/
			this.base_scale = this.FRAME_HEIGHT / this.originalHeight;
			cc.log("base_scale " + this.base_scale);

			this.baseCenterX = this.originalWidth / 2;
			this.baseCenterY = this.originalHeight / 2;
			
			this.dx = 0;
			this.dy = 0;
			
		} else {
			
			// 既にある場合は削除
			this.removeChild(this.illust);
		}

        var rect3 = this.getRectForClipArea();

        this.illust = cc.Sprite.create(this.image_file_path, rect3);

		var rect1 = cc.rect(this.FRAME_X,this.FRAME_Y1,this.FRAME_WIDTH,this.FRAME_HEIGHT);
        var rect2 = cc.rect(this.FRAME_X,this.FRAME_Y2,this.FRAME_WIDTH,this.FRAME_HEIGHT);

        this.addChild(this.illust);

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
        this.illust.setPosition(cx,cy);
		
		// 拡大率の調整（高さに合わせる）
		var new_scale = this.FRAME_HEIGHT / rect3.height;
		
		this.illust.setScale( new_scale );
    },


    //スプライトをカットするための領域を取得
    getRectForClipArea:function(){
		
		var scale = this.scale * this.base_scale;

		cc.log("初期スケール : " + scale);
		
		// リクエストされた画像の拡大率
		var cw = this.originalWidth / scale;
		var ch = this.originalHeight / scale;
		
		// スケールが1を超えたら
		if( scale > 1 ){
			cw = this.originalWidth * scale;
			ch = this.originalHeight * scale;
		}
		
		// 拡大した画像領域がフレームより大きくなる場合
		if( this.originalWidth * scale > this.FRAME_WIDTH ){
			cw = this.FRAME_WIDTH / scale;
		}
		if( this.originalHeight * scale > this.FRAME_HEIGHT ){
			ch = this.FRAME_HEIGHT / scale;
		}
		
		// 拡大を中心から拡大するようにする為の処理
		var cx = (this.baseCenterX - cw / 2) + this.offsetX;
		var cy = (this.baseCenterY - ch / 2) + this.offsetY;

		// 座標が0より小さい場合はゼロにする
		if(cx < 0){
			cx = 0;
			this.offsetX += this.dx;
		} else if (cx > this.originalWidth){
			cx = this.originalWidth;
		}
		if(cy < 0){
			cy = 0;
			this.offsetY -= this.dy;
		} else if (cy >= this.originalHeight){
			cy = this.originalHeight;
		}

		// 座標位置より画像の縦横の上限を制御
		if(cw + cx > this.originalWidth){
			cw = this.originalWidth;
			this.offsetX += this.dx;
		}
		if(ch + cy > this.originalHeight){
			ch = this.originalHeight;
			this.offsetY -= this.dy;
		}
		
		this.imageX = cx;
		this.imageY = cy;
		
		return cc.rect( cx , cy , cw, ch);
    },
    getScale:function(){
        return this.illust.getScale();
    }
});
