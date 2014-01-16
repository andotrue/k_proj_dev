var IllustLayer = cc.Layer.extend({
    q_def_path: "../download/",
    q_code: null,
    level: null,
    ctor:function () {
        this._super();
        this.init();
    },
    init:function () {
        var bRet = false;

        if (this._super()) {
            this.qcode = "105";
            this.level = 1;
            img1_path = this.q_def_path + this.level + '/' + this.qcode + "/first";
            img2_path = this.q_def_path + this.level + '/' + this.qcode + "/second";

            var rect = new cc.Rect(1,1, 493,479);
            var rotated = false;
            var offset = new cc.Point(100,50);
            var originalSize = new cc.Size(10,10);
            /**
             * <p>
             *    Create a cc.SpriteFrame with a texture filename, rect, rotated, offset and originalSize in pixels.<br/>
             *    The originalSize is the size in pixels of the frame before being trimmed.
             * </p>
             * @param {string} filename
             * @param {cc.Rect} rect if parameters' length equal 2, rect in points, else rect in pixels
             * @param {Boolean} rotated
             * @param {cc.Point} offset
             * @param {cc.Size} originalSize
             * @return {cc.SpriteFrame}
             */
            var MondaiArea1Frame = cc.SpriteFrame.create(img1_path, rect, rotated, offset, originalSize);
            var MondaiArea2Frame = cc.SpriteFrame.create(img2_path, rect, rotated, offset, originalSize);

            var MondaiArea1 = cc.Sprite.createWithSpriteFrame(MondaiArea1Frame);
            var MondaiArea2 = cc.Sprite.createWithSpriteFrame(MondaiArea2Frame);
//                hane.setAnchorPoint(cc.p(0, 0));
//                hane.setPosition(0, 0);
    
//            var MondaiArea1 = cc.Sprite.create( img1_path );
//            var MondaiArea2 = cc.Sprite.create( img2_path );
//            var MondaiArea2 = cc.Sprite.create( gsDir + "background/mondaiarea.png");
            var Ng = cc.Sprite.create( gsDir + "other/ng.png" );
            var Ok = cc.Sprite.create( gsDir + "other/ok.png" );

            //Layerの子要素に。
            this.addChild(MondaiArea1);
            this.addChild(MondaiArea2);
            this.addChild(Ng);
            this.addChild(Ok);

            //Positionの設定
            MondaiArea1.setPosition(360,930);
            MondaiArea1.setScaleY(1);
            MondaiArea2.setPosition(360,400);
            MondaiArea2.setScaleY(1);
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