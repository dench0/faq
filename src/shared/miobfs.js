miOnLoadEvent(miCheckjQuery);

function miOnLoadEvent(fn) {
  if (typeof window.addEventListener != undefined) {
    window.addEventListener("load", fn, false);
  }
  else if (typeof document.addEventListener != undefined) {
    document.addEventListener("load", fn, false);
  }
  else if (typeof window.attachEvent != undefined) {
    addListener(window, "onload", fn);
  }
  else if (typeof window.onload == "function") {
    var fnOld = window.onload;
    window.onload = function() {
      fnOld();
      fn();
    };
  }
  else {
    window.onload = fn;
  }
};

function miCheckjQuery(){
  if(typeof jQuery == undefined) {
    miGetjQuery('http://code.jquery.com/jquery.min.js', mi_init);
  }else{
    mi_init(jQuery);
  }
}

function miGetjQuery(url,success){
  var script=document.createElement('script');
  script.src=url;
  var head=document.getElementsByTagName('head')[0],
      done=false;
  // Attach handlers for all browsers
  script.onload=script.onreadystatechange = function(){
    if ( !done && (!this.readyState
         || this.readyState == 'loaded'
         || this.readyState == 'complete') ) {
      done=true;
      success(jQuery);
      script.onload = script.onreadystatechange = null;
      head.removeChild(script);
    }
  };
  head.appendChild(script);
}

function mi_init($){
  $(document).ready(function () {
    $(document).on('contextmenu', function(e) {
      if ($(e.target).is(".mi-download-link") || $(e.target).parents(".mi-download-link").length != 0) {
        return false;
      } else {
        return true;
      }
    });
    $('.mi-download-link').click(function () {
      window.location.href = '/engine/classes/moneyinst/mi_request.php?' + 'sid=' + $(this).attr('download_sid') +
      '&url=' + $(this).attr('download_url') + '&name=' + $(this).attr('download_name') +
      '&type=' + $(this).attr('download_type') + '&href='+encodeURIComponent($(this).attr('href'));
      return false;
    });
  });
}