$.ajax({
    url: "php/fetch_scoreData.php",
    dataType: "json",
    type: "GET",
    success: function(data){
        var hot = data.filter(function(item, index){
            return item.hot == "true";
        })
        var other = data.filter(function(item, index){
            return item.hot == "false";
        })
        hot.sort(function(a, b){return b.rate - a.rate});
        other.sort(function(a, b){return b.rate - a.rate});
        var background = ["", "class='gray'"];
        var diff_color = {"BASIC":"style=color:lightgreen", "ADVANCED":"style=color:yellow", "EXPERT":"style=color:red", "MASTER":"style=color:purple"};
        var hot_rate = 0, other_rate = 0;
        for(var idx = 0; idx < 15; idx++){
            var val = hot[idx];
            var rate = Math.round(hot[idx].rate * 100)/100;
            var title = remove_quot(hot[idx].title);
            var diff = remove_quot(hot[idx].difficulty);
            hot_rate += rate / 15;
            $('#hot_target').append("<tr " + background[idx%2] + "><td>" + (idx+1) + "</td><td>" + title + "</td><td " + diff_color[diff] + ">" + diff
                + "</td><td align='right'>" + val.techScore + "</td><td align='right'>" + val.const + "</td><td align='right'>" + rate + "</td></tr>");
        }
        for(var idx = 0; idx < 30; idx++){
            var val = other[idx];
            var rate = Math.round(other[idx].rate*100)/100;
            var title = remove_quot(other[idx].title);
            var diff = remove_quot(other[idx].difficulty);
            other_rate += rate / 30;
            $('#other_target').append("<tr " + background[idx%2] + "><td>" + (idx+1) + "</td><td>" + title + "</td><td " + diff_color[diff] + ">" + diff
                + "</td><td align='right'>" + val.techScore + "</td><td align='right'>" + val.const + "</td><td align='right'>" + rate + "</td></tr>");
        }
        var max_rate = Math.max(hot[0].rate, other[0].rate);
        var max = (max_rate * 10 + hot_rate * 15 + other_rate * 30) / 55;
        max = Math.round(max*100)/100;
        var hot_other = (hot_rate + other_rate*2) / 3;
        hot_other = Math.round(hot_other*100)/100;
        hot_rate = Math.round(hot_rate*100)/100;
        other_rate = Math.round(other_rate*100)/100;
        $('#summary').append('<tr><td align="right">' + hot_rate + '</td><td align="right">' + other_rate + '</td><td align="right">' + hot_other + '</td><td align="right">' + max + '</td></tr>');
    }
});
function remove_quot(s){
    var rel = "";
    for(var i = 0; i < s.length; i++){
        var c = s.substr(i, 1);
        if(c != '"' && c != "'") rel = rel + c;
    }
    return rel;
}
