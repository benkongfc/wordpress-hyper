(function($){
  
window.addEventListener('popstate', function(e) {
  renderJson('', JSON.parse(localStorage.getItem("tsr-"+document.location.href)));
});

onHotLoad(); 
if($("script#contentJson").length){
  var contentJson = $("script#contentJson").html().trim();
  if(contentJson.length > 10){
    localStorage.setItem("tsr-"+document.location.href, contentJson);
    localStorage.setItem("tsr-t-"+document.location.href, + new Date());
  }
}
function renderJson(href, data){
  if(typeof data.templates === 'undefined')
    return false;
  var partials = {};
  var output = '';
  $.each(data.partials, function(idx, p) {
    partials[idx] = $('script[mustache-id="'+p+'"]').html().replace("/</re_script>/", "</script>");
  });
  $.each(data.templates, function(idx, t) {
    output += Mustache.render($('script[mustache-id="'+t+'"]').html().replace("/</re_script>/", "</script>"), data, partials);
  });
  $('#appDiv').html(output);
  if(href)
    history.pushState(null, null, href);
  onHotLoad(); 
  return true;
}
function paging_out(){
  var r = $.Deferred();
  if(isMobile()){
    $("#appDiv").animate({'margin-left': '100%'}, 200, 'linear', function() {
        $("#appDiv").html($('script[mustache-id="loading.mustache"]').html());
        $("#appDiv").css("margin-left", "-100%");
        $("#appDiv").css("margin-right", "100%");
        r.resolve();
      }
    );
    return r;
  }else{
    $("#appDiv").append($('script[mustache-id="loading.mustache"]').html());
    return true;
  }
}

function paging_in(){
  var r = $.Deferred();
  if(isMobile()){
    $("#appDiv").animate({'margin-left': '0px', 'margin-right' : '0px'}, 200, 'linear', function() {
        $("#appDiv").removeClass("ani-enter");
        r.resolve();
      }
    );
    return r;
  }else{
    $("#loading_overlay").remove();
    return true;
  }
}
function onHotLoad() {
  
  $(".autoRenderLink").click(function() {
    if(!in_ajax)
      return true;
      
    var href = $(this).attr('href');
    if(href.indexOf('://') == -1)
      href = location.origin + href;
    var oldData = '';
    var t = localStorage.getItem("tsr-t-"+href);
    if((+ new Date()) - t < 60*60*12){
      oldData = JSON.parse(localStorage.getItem("tsr-"+href));  
    }
    var el = this;
    var aj = $.ajax({
        url: href,
        method: 'POST',
        data: { in_ajax: 1 },
        success: function(data){
          if(href.indexOf('://') == -1)
            href = location.origin + href;
          localStorage.setItem("tsr-"+href, JSON.stringify(data));
          localStorage.setItem("tsr-t-"+href, + new Date());
          if(!oldData){
            oldData = data;
          }
        }
    });
    $.when(paging_out()).done(function() {
      if(oldData){
        renderJson(href, oldData);
        paging_in();
      }else{
        $.when(aj).done(function (){
          renderJson(href, oldData);
          paging_in();
        });
      }
    });
    return false;
  });
}
window.isMobile = function(){
  if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && !(/LG-H/.test(navigator.userAgent)))
    return true;
  return false; 
}
})(jQuery); //$
