let reader = new FileReader();

reader.onload = function(ev){
  processData(ev.target.result);
}

function submit(){
  var file = $('#data')[0].files[0];
  reader.readAsText(file);
}

function processData(raw_) {
  var json = JSON.parse(raw_);
  $.each(json, function(i, val){
    var search = val.name.match(/[a-zA-Z0-9]|[ぁ-ヶ]|[一-龠]/g).join("");
    val.search = search;
  })
  var req = {request : JSON.stringify(json)};
  $.ajax({
    url : 'php/jsonLoader.php',
    type : 'post',
    data : req,
    success : function(data){console.log(data);},
    error : function(){console.log('NG');}
  });

}

/*

  TODO :
  preg_match_all() だとうまく英数字日本語以外を弾けない(記号が残ってしまう)
  のでデータ処理部分を js で書き、PHP はデータベース操作部分だけ

*/
