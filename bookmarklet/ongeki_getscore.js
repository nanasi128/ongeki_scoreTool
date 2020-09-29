
var CSVHeader = "name,difficulty,level,techScore,rank,next,AB,FB,constant,rate,hot\r\n";
$(function(){
    main();
});
async function main(){
    var master = await getScore("MASTER");
    var expert = await getScore("EXPERT");
    var lunatic = await getScore("LUNATIC");
    var cache = master.concat(expert);
    var scoreData = cache.concat(lunatic);
    var hotScore = scoreData.filter(function(item){
        if(item["hot"]) return true;
    });
    var oldScore = scoreData.filter(function(item){
        if(!item["hot"]) return true;
    });
    hotScore.sort(function(a,b){
        return(b["rate"] - a["rate"]);
    });
    oldScore.sort(function(a,b){
        return(b["rate"] - a["rate"]);
    });
    toCSV(scoreData);
}
function getScore(_diff){
    const Diff = {"EXPERT":2, "MASTER":3, "LUNATIC":10};
    const URL = "https://ongeki-net.com/ongeki-mobile/record/musicWord/search/?word=99&diff=";
    var scoreData = [];
    return new Promise(resolve => { 
        $.ajax({
        url: URL + Diff[_diff],
        success: function(data){
            console.log("name,difficulty,level,techScore,rank,next,AB,FB,constant,rate,hot");
            var forms = $(data).find('form');
            $.each(forms, function(i, val){
                if(val.children.length == 4){
                var name = forms[i].childNodes[1].childNodes[5].textContent;
                var level = forms[i].childNodes[1].childNodes[1].childNodes[3].textContent
                var techScore = scoreToInt(forms[i].childNodes[3].childNodes[1].childNodes[2].childNodes[5].textContent);
                var FB = forms[i].childNodes[5].childNodes[5].src.length == 60 ? false : true;
                var AB = forms[i].childNodes[5].childNodes[7].src.length == 60 ? false : true;
                }else if(val.children.length == 2){
                    var name = forms[i].childNodes[1].childNodes[5].childNodes[0].textContent;
                    var level = forms[i].childNodes[1].childNodes[1].childNodes[3].textContent;
                    var techScore = 0;
                    var FB = false;
                    var AB = false;
                }
                if(i != 0) {
                    var constant, hot;
                    $.each(Const_JSON, function(i, val){
                        if(val.Name == name && val.Difficulty == _diff){
                            if(val.Constant != "") constant = Number(val.Constant);
                            else constant = 0;
                            hot = val.Hot == '1' ? true : false;
                        }
                    });
                    if(typeof hot === "undefined") hot = false;
                    if(typeof constant !== "undefined"){
                        var rate = calRate(techScore, constant);
                    }else {
                        var constant = 0;
                        var rate = 0;
                    }
                    var rank = String(calRank(techScore)).split(',');
                    // console.log(calRank(techScore));
                    var arr = {name:name, difficulty:_diff, level:level, techScore:techScore,
                       rank:rank[0], next:rank[1], AB:AB, FB:FB, const:constant, rate:rate, hot:hot};
                    scoreData.push(arr);
                    //console.log(Object.values(arr).join());
                }
            });
            resolve(scoreData);
        }
    });
    })
}

function scoreToInt(_score){
    var split = _score.split(",");
    var score = "";
    for(var i = 0; i < split.length; i++){
        score += split[i];
    }
    return score;
}

function calRate(_score, _constant){
    var score = Number(_score);
    var constant = Number(_constant);
    if(score >= 1007500){
        return constant + 2;
    }else if(score >= 1000000){
        return (score - 1000000) * 0.01 / 150 + 1.5 + constant;
    }else if(score >= 990000){
        return (score - 990000) * 0.01 / 200 + 1.0 + constant;
    }else if(score >= 970000){
        return (score - 970000) * 0.01 / 200 + constant;
    }
    return 0;
}

function calRank(_score){
    var border = [1010000,1007500,1000000,990000,975000,940000,900000,850000];
    var rank = ["MAX","SSS+","SSS","SS","S","AAA","AA","A"];
    if(_score >= border[0]) return "MAX,0";
    for(var i = 1; i < rank.length; i++){
        if(_score >= border[i]) return rank[i] + "," + String(border[i-1] - _score); 
    }
    if(_score == 0) return "NO PLAY,0";
}

function toCSV(_scoreData){
    var csvData = CSVHeader;
    for(var i = 0; i < _scoreData.length; i++){
        _scoreData[i]["name"] = '"' + _scoreData[i]["name"] + '"';
        var cache = Object.values(_scoreData[i]).join() + '\r\n';
        csvData += cache;
    }
    var bom = new Uint8Array([0xEF, 0xBB, 0xBF]);
    var blob = new Blob([bom, csvData], {type: 'text/csv'});
    var url = window.URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.download = 'ongeki_scoredata.csv';
    a.href = url;
    a.click();
}