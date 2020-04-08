let scoreData;
let sorted = [];
let stat = [];
const diff = ["BASIC", "ADVANCED", "EXPERT", "MASTER", "LUNATIC"];
const rank = ["A","AA","AAA","S", "SS", "SSS", "SSS+", "MAX"];
const background = ["", "class='gray'"];
const diff_color = {"BASIC":"style=color:lightgreen", "ADVANCED":"style=color:yellow", "EXPERT":"style=color:red", "MASTER":"style=color:purple"};
$(function(){
    for(var i = 0; i < diff.length; i++){
        var temp = [];
        for(var j = 0; j < rank.length; j++){
            temp[rank[j]] = 0;
        }
        stat[diff[i]] = temp;
    }
    $.each($('.level_ch'), function(idx, val){
        val.checked = true;
    });
    $.each($('.diff_ch'), function(idx, val){
        val.checked = true;
    });
    $.ajax({
        url: "fetch_scoreData.php",
        type: "get",
        dataType: "json",
        success: function(data){
            scoreData = data;
            sorted = data;
            $.each(data, function(idx, val){
                val.name = remove_quot(val.name);
                val.difficulty = remove_quot(val.difficulty);
                $('#scoreTable').append("<tr " + background[idx%2] +"><td>" + val.name + "</td><td align='center '" + diff_color[val.difficulty] + ">" + val.difficulty
                    + "</td><td align='right'>" + val.level + "</td><td align='right'>" + val.techScore + "</td><td align='right'>" + val.rank
                    + "</td><td style='width:75px;' align='right'>" + val.next + "</td><td>" + val.AB + "</td><td>" + val.FB + "</td></tr>");
                if(val.rank != "NO PLAY"){
                    stat[val.difficulty][val.rank]++;
                }
            });
            for(var i = 0; i < Object.keys(stat).length; i++){ //$.each()だとなんかうまくいかなかった
                val = stat[diff[i]];
                $('#statistics').append("<tr align='right'><td>" + diff[i] + "</td><td>" + val["S"] + "</td><td>" + val["SS"] + "</td><td>"
                    + val["SSS"] + "</td><td>" + val["SSS+"] + "</td><td>" + val["MAX"] + "</td></tr>");
            }
        },
        error: function(j, t, e){
            console.log(e);
        }
    });
});
function reflesh(){
    sorted = [];
    
    // reset table
    while($('#scoreTable tr')[1]){
        $('#scoreTable tr')[1].remove();
    }
    
    var checked_level = [];
    $.each($('.level_ch'), function(idx, val){
        if(val.checked){
            checked_level.push(val.classList[1]);
        }
    });
    var checked_difficulty = [];
    $.each($('.diff_ch'), function(idx, val){
        if(val.checked){
            checked_difficulty.push(val.classList[1]);
        }
    })
    var idx = 0;
    $.each(scoreData, function(i, val){
        $.each(checked_level, function(j, v){
            if(val.level == v){
                $.each(checked_difficulty, function(k, value){
                    if(val.difficulty == value){
                        sorted.push(val);
                        $('#scoreTable').append("<tr " + background[idx%2] + "><td>" + val.name + "</td><td " + diff_color[val.difficulty] + ">" + val.difficulty
                        + "</td><td>" + val.level + "</td><td>" + val.techScore + "</td><td>" + val.rank
                        + "</td><td>" + val.next + "</td><td>" + val.AB + "</td><td>" + val.FB + "</td></tr>");
                        idx++;
                    }      
                });
            }
        })
    })
}
function selectAll(cl){
    $.each($(cl), function(idx, val){
        val.checked = true;
    })
}
function deselectAll(cl){
    $.each($(cl), function(idx, val){
        val.checked = false;
    })
}
function sort_column(index){
    levelList = {'1':1, '2':2, '3':3, '4':4, '5':5, '6':6, '7':7, '7+':7.5, '8':8, '8+':8.5, '9':9, '9+':9.5, '10':10, '10+':10.5,
        '11':11, '11+':11.5, '12':12, '12+':12.5, '13':13, '13+':13.5, '14':14, '14+':14.5 };
    difficultyList = {'BASIC':1, 'ADVANCED':2, 'EXPERT':3, 'MASTER':4, 'LUNATIC':5};
    current = $('.' + index).val();
    $('.sort').val("");
    while($('#scoreTable tr')[1]){
        $('#scoreTable tr')[1].remove();
    }
    switch(current){
        case "" :
            $('.'+index).val("↑");
            sorted.sort(function(a,b){
                if(index == "level") return(levelList[a[index]] - levelList[b[index]]);
                if(index == "difficulty") return(difficultyList[a[index]] - difficultyList[b[index]]);
                return(a[index] - b[index]);
            });
            break;
        case "↓" :
            $('.'+index).val("↑");
            sorted.sort(function(a,b){
                if(index == "level") return(levelList[a[index]] - levelList[b[index]]);
                if(index == "difficulty") return(difficultyList[a[index]] - difficultyList[b[index]]);
                return(a[index] - b[index]);
            });
            break;
        case "↑" :
            $('.'+index).val("↓");
            sorted.sort(function(a,b){
                if(index == "level") return(levelList[b[index]] - levelList[a[index]]);
                if(index == "difficulty") return(difficultyList[b[index]] - difficultyList[a[index]]);
                return(b[index] - a[index]);
            });
            break;
        default:
            break;
    }
    $.each(sorted, function(idx, val){
        $('#scoreTable').append("<tr " + background[idx%2] + "><td>" + val.name + "</td><td " + diff_color[val.difficulty] + ">" + val.difficulty
                + "</td><td>" + val.level + "</td><td>" + val.techScore + "</td><td>" + val.rank
                + "</td><td>" + val.next + "</td><td>" + val.AB + "</td><td>" + val.FB + "</td></tr>");
    })
}

function remove_quot(s){
    var rel = "";
    for(var i = 0; i < s.length; i++){
        var c = s.substr(i, 1);
        if(c != '"' && c != "'") rel = rel + c;
    }
    return rel;
}