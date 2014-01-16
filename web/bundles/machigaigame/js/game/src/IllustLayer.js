var IllustLayer = cc.Layer.extend({
    q_def_path: "../download/",
    q_code: null,
    level: null,
    illusts:[],
    FIRST: 1,
    SECOND: 2,
    offset: null,
    originalSize: null,
    ctor:function () {
        this._super();
        this.init();
    },
    move:function(dx,dy){
      this.offset = new cc.p( this.offset.x + dx, this.offset.y + dy);
      this.updateIllusts();
    },
    img_path:function(index){
      switch(index){
        case 1:
          postfix = "/first";
          break;
        case 2:
          postfix = "/second";
          break;
      }
      return this.q_def_path + this.level + '/' + this.qcode + "/first";
    },
    getFrame:function(index){
      var rotated = false;
      return cc.SpriteFrame.create( this.img_path(index), this.rect, rotated, this.offset, this.originalSize);
    },
    updateIllusts:function(){
      for (var index = this.FIRST; index <= this.SECOND; index++) {
        if( this.illusts[index] !== undefined){
          this.illusts[index].removeFromParent();
        }

        this.illusts[index] = cc.Sprite.createWithSpriteFrame(this.getFrame(index));
        this.addChild( this.illusts[index]);
        this.illusts[index].setPosition(360,930 - 530* (index -1));
      }
    },
    init:function () {
        var bRet = false;

        if (this._super()) {
            this.qcode = "105";
            this.level = 1;

            this.rect = new cc.Rect(1,1, 300,300);
            this.offset = new cc.Point(100,50);
            this.originalSize = new cc.Size(1000,1000);

            this.updateIllusts();
            this.move(100,200);
//          hane.setAnchorPoint(cc.p(0, 0));
    
            var Ng = cc.Sprite.create( gsDir + "other/ng.png" );
            var Ok = cc.Sprite.create( gsDir + "other/ok.png" );

            //Layerの子要素に。
            this.addChild(Ng);
            this.addChild(Ok);

            //Positionの設定
//            MondaiArea1.setScaleY(1);
//            MondaiArea2.setScaleY(1);
            Ng.setPosition(640,960);
            Ok.setPosition(640,960);

            bRet = true;
        }
        return bRet;
    },
/*
    onTouchesBegan: function(touches, event){
      //cc.log('onTouchesBegan:' + touches.length);
      for (var i=0; i < touches.length; i++) {
        this.processEvent(touches[i]);
      }
    },
    onTouchesMoved: function(touches, event){
      //cc.log('onTouchesMoved' + touches.length);
      for (var i=0; i < touches.length; i++) {
        this.processEvent(touches[i]);
      }
    },
    onTouchBegan:function (touch, event) {
      processEvent(touch);
    },
    onTouchMoved:function (touch, event) {
      processEvent(touch);
    },
    processEvent: function(touch){
/*       if(this.eating || !touch){
        return;
      }
   
      this.eating = true;

      var touchLocation = touch.getLocation();
      var local = this.convertToNodeSpace(touchLocation);
      var offset = (local.x + App.center().x) / App.size().width;
      var hae = null;
      for (var i = 0; i < 5; i++) {
        var _hae = this.getChildByTag(i);
        if (!_hae) {
          continue;
        }
        var haeLocal = _hae.convertToNodeSpace(touchLocation);
        var r = _hae.rect();
        r.x = 0;
        r.y = 0;
        if (cc.rectContainsPoint(r, haeLocal)){
          cc.log('hae' + _hae.getTag() + ' isTouch!');
          hae = _hae;
          this.haecount--;
          break;
        }
      }
      if(!hae){
        this.eating = false;
        this.kaeru.kerokero();
        return;
      }
      this.kaeru.beron(local, function() {
        if (hae) {
          hae.runAction(cc.Sequence.create([
            cc.MoveTo.create(0.2, cc.p(-30 + (offset * 100), -250)),
            cc.FadeOut.create(0.05),
            cc.CallFunc.create(function(){
              hae.removeFromParent();
              this.eating = false;
              this.kaeru.perori(function() {
                if(!self.eating){
                  this.start();
                 }
              }.bind(this));
            }.bind(this))
          ]));
        } else {
          this.kaeru.kyorokyoro();
          this.eating = false;
        }
      }.bind(this));
    },
*/    
});