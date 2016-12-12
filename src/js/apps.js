function validateURL(textval) {
  var urlregex = /^(http|https):\/\/(([a-zA-Z0-9$\-_.+!*'(),;:&=]|%[0-9a-fA-F]{2})+@)?(((25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])(\.(25[0-5]|2[0-4][0-9]|[0-1][0-9][0-9]|[1-9][0-9]|[0-9])){3})|localhost|([a-zA-Z0-9\-\u00C0-\u017F]+\.)+([a-zA-Z]{2,}))(:[0-9]+)?(\/(([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*(\/([a-zA-Z0-9$\-_.+!*'(),;:@&=]|%[0-9a-fA-F]{2})*)*)?(\?([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?(\#([a-zA-Z0-9$\-_.+!*'(),;:@&=\/?]|%[0-9a-fA-F]{2})*)?)?$/;
  return urlregex.test(textval);
}
$.urlParam = function(name){
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if (results==null){
       return null;
    }
    else{
       return results[1] || 0;
    }
}
function changeLinks(){
  $("a.open-via-lobby").each(function(){
    p = $(this).data("path");
    if(typeof localStorage["lobbyURL"] === "undefined" || localStorage["lobbyURL"] == "" || localStorage["lobbyURL"] === "null"){
      $(this).unbind("click").bind("click", function(){
        if(confirm("You have to set Lobby URL. Please do it so by clicking the settings icon in the apps navbar.\n\nPress Ok if you want to change now."))
          $("#change_lobby_url").click();
      });
      localStorage["lobbyURL"] = "";
    }else{
      $(this).unbind("click").attr({
        href: localStorage["lobbyURL"] + p,
        target: '_blank'
      });
    }
  });
}
var setLobbyURL = function(url){
  if(validateURL(url)){
    localStorage["lobbyURL"] = url;
    return true;
  }else
    return false
};
lobby.load(function(){
  $('.dropdown-button').dropdown({
      inDuration: 300,
      outDuration: 225,
      constrain_width: false, // Does not change width of dropdown to that of the activator
      gutter: 0, // Spacing from edge
      belowOrigin: false, // Displays dropdown below the button
      alignment: 'left' // Displays dropdown with edge aligned to the left of button
  });
  $('.modal-trigger').leanModal({
    ready: function(){
      $('#workspace .modal-content #lobby_url').val(localStorage["lobbyURL"]);
    }
  });
  
  $('#workspace .modal-footer #save').live('click', function(e){
    if(setLobbyURL($('#workspace .modal-content #lobby_url').val()))
      changeLinks();
    else
      alert("Invalid URL");
  });
  
  urlParam = decodeURIComponent($.urlParam('lobby_url'));
  if(urlParam !== "null"){
    setLobbyURL(urlParam);
  }
});
$(document).ready(function(){
  changeLinks();
});
