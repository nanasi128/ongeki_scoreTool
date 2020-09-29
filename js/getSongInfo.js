let domparser = new DOMParser();
let reader_old = new FileReader();
let reader_hot = new FileReader();

reader_old.onload = function(ev){
  processData(ev.target.result, 'false');
}
reader_hot.onload = function(ev){
  processData(ev.target.result, 'true');
}

function readData(){
  $.ajax({
    url : 'php/initConstTable.php',
    type : 'get'
  });
  var file_old = $('#old_data')[0].files[0];
  reader_old.readAsText(file_old);
  var file_hot = $('#hot_data')[0].files[0];
  reader_hot.readAsText(file_hot);
}

function processData(raw_, is_hot_){
  var doc = domparser.parseFromString(raw_, 'text/html');
  var tables = doc.getElementsByTagName('table');
  var songData = [];
  $.each(tables, function(i, table){
    var id = table.id;
    if(id.substring(0, 15) == 'ui_wikidb_table'){
      $.each(table.children[1].children, function(j, tr){
        var title = tr.children[3].innerText;
        var search = title.match(/[a-zA-Z0-9]|[ぁ-ヶ]|[一-龠]/g).join("");
        var o = {
          title : title,
          difficulty : tr.children[0].innerText,
          level : tr.children[7].innerText,
          const : tr.children[6].innerText,
          hot : is_hot_,
          search : search
        };
        songData.push(o);
      });
    }
  });
  console.log(songData);
  var data = {request : JSON.stringify(songData)};
  $.ajax({
    url : "php/loadConstJSON.php",
    type : "post",
    data : data,
    success : function(){console.log("OK");},
    error : function(err){console.log(err);}
  });
}
