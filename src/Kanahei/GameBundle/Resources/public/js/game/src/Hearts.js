var Hearts = cc.Class.extend({
    _MAX: 10,
    _MIN: 0,
    _INITIAL_VALUE: 10,
    _count: 0,
    _objWidth: 17,
    _objHeight: 22,
    _objX: 420,
    _objY: 1320,
    _sprites: [],
    parent: null,
    _file_path_off: gsDir + "other/game_heart_off.png",
    _file_path_on: gsDir + "other/game_heart_on.png",
    ctor:function(parent, failLimit){
        if(parent === undefined) throw("Heart.ctor: parent must not be undefined!");
        this.init(parent, failLimit);
    },
    init:function(parent,failLimit){
        this.parent = parent;
        this._count = failLimit;
        this._MAX = failLimit;
        for (var i = 0; i < this._MAX; i++) {
            this._changeSprite(i, this._file_path_off);
        }
    },
    count:function(){
        return this._count;
    },
    increment:function(){
        if(this.count() >= this._MAX) throw "Hearts: Over the Min Heart counts.";
        var cursor = this.count();
        this._changeSprite(cursor, this._file_path_on);
        this._count += 1;
        return this.count();
    },
    decrement:function(){
        if(this.count() <= this._MIN) throw "Hearts: Over the Min Heart counts.";
        var cursor = this.count()-1;
        this._changeSprite(cursor, this._file_path_on);
        this._count -= 1;
        return this.count();
    },
    _changeSprite:function(index, file_path){
        var heart  = cc.Sprite.create( file_path );
        if (this._sprites[index] !== undefined)
            this._sprites[index].removeFromParent();
        this._sprites[index] = heart;
        this.parent.addChild(heart);
        heart.setPosition(this._objX + this._objWidth * index ,this._objY);
    }
});