//var gNotification = cc.NotificationCenter.getInstance();
//var gSpriteFrameCache = cc.SpriteFrameCache.getInstance();

//var gSharedEngine = cc.AudioEngine.getInstance();
/*
var MUSIC_BACKGROUND  = "audio/musicByFoxSynergy.mp3";
var EFFECT_BUTTON_CHICK  = "audio/effect_buttonClick.ogg";
var EFFECT_GAME_FAIL  = "audio/effect_game_fail.ogg";
var EFFECT_GAME_WIN  = "audio/effect_game_pass.ogg";
var EFFECT_PATTERN_UN_SWAP  = "audio/effect_unswap.ogg";
var EFFECT_PATTERN_CLEAR  = "audio/effect_clearPattern.ogg";
var EFFECT_PATTERN_BOMB  = "audio/effect_bombPattern.ogg";
var EFFECT_TIME_WARN  = "audio/effect_timewarning.ogg";
*/
// res/game_scene/
var gsDir = rd + 'game_scene/';
var g_resources = [
    {src: gsDir + "background/footer.png"},
    {src: gsDir + "background/header.png"},
    {src: gsDir + "background/mondaiarea.png"},
    {src: gsDir + "background/ranking_userinfoarea.png"},
    {src: gsDir + "background/result_menu.png"},
    {src: gsDir + "background/result_menu_guest.png"},
    {src: gsDir + "button/game_icon_giveup.png"},
    {src: gsDir + "button/game_icon_hint.png"},
    {src: gsDir + "button/game_icon_save.png"},
    {src: gsDir + "label/clear.png"},
    {src: gsDir + "label/game_kanahei.png"},
    {src: gsDir + "label/miss.png"},
    {src: gsDir + "label/game_otetsuki.png"},
    {src: gsDir + "label/game_timelimit.png"},
    {src: gsDir + "number/game_number_0.png"},
    {src: gsDir + "number/game_number_1.png"},
    {src: gsDir + "number/game_number_2.png"},
    {src: gsDir + "number/game_number_3.png"},
    {src: gsDir + "number/game_number_4.png"},
    {src: gsDir + "number/game_number_5.png"},
    {src: gsDir + "number/game_number_6.png"},
    {src: gsDir + "number/game_number_7.png"},
    {src: gsDir + "number/game_number_8.png"},
    {src: gsDir + "number/game_number_9.png"},
    {src: gsDir + "number/coron.png"},
    {src: gsDir + "number/result_0.png"},
    {src: gsDir + "number/result_1.png"},
    {src: gsDir + "number/result_2.png"},
    {src: gsDir + "number/result_3.png"},
    {src: gsDir + "number/result_4.png"},
    {src: gsDir + "number/result_5.png"},
    {src: gsDir + "number/result_6.png"},
    {src: gsDir + "number/result_7.png"},
    {src: gsDir + "number/result_8.png"},
    {src: gsDir + "number/result_9.png"},
    {src: gsDir + "number/result_coron.png"},
    {src: gsDir + "number/result_pt.png"},
    {src: gsDir + "other/game_heart_off.png"},
    {src: gsDir + "other/game_heart_on.png"},
    {src: gsDir + "other/ng.png"},
    {src: gsDir + "other/ok.png"},
    {src: gsDir + "other/slidebar.png"},
    {src: gsDir + "other/slideicon.png"},
    {src: gsDir + "other/sort.png"},
    {src: gsDir + "other/game_star_off.png"},
    {src: gsDir + "other/game_star_on.png"},
    {src: gsDir + "popup/common.png"},
    {src: gsDir + "popup/gamestart.png"},
    {src: gsDir + "popup/gamestart_first.png"},
    {src: gsDir + "popup/gamestart_guest.png"},
    {src: gsDir + "popup/gamestart_notfirst.png"},
    {src: gsDir + "popup/giveup.png"},
    {src: gsDir + "popup/hint.png"},
    {src: gsDir + "popup/questiondownload.png"},
    {src: gsDir + "popup/save.png"},
    {src: gsDir + "popup/save_guest.png"}
/*
    {src:MUSIC_BACKGROUND},
    {src:EFFECT_BUTTON_CHICK},
    {src:EFFECT_GAME_FAIL},
    {src:EFFECT_GAME_WIN},
    {src:EFFECT_PATTERN_UN_SWAP},
    {src:EFFECT_PATTERN_CLEAR},
    {src:EFFECT_PATTERN_BOMB},
    {src:EFFECT_TIME_WARN}
*/
];

var gScoreData = {lastScore:0,bestScore:0};

var eGameMode = {
    Invalid : -1,
    Challenge:0,
    Timer:1,
    Count:2
};
var gGameMode = eGameMode.Challenge;

gScoreData.setLastScore = function(score){
    this.lastScore = score;

    if (score > this.bestScore)
    {
        this.bestScore = score;
        sys.localStorage.setItem('bestScore',this.bestScore);
    }
    sys.localStorage.setItem('lastScore',this.lastScore);
};

gScoreData.initData = function(){
    if( sys.localStorage.getItem('gameData') == null){
        sys.localStorage.setItem('bestScore','0');
        sys.localStorage.setItem('lastScore','0');

        sys.localStorage.setItem('gameData',33) ;
        return;
    }

    this.bestScore = parseInt(sys.localStorage.getItem('bestScore'));
};

