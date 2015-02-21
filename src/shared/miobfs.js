(function(funcName, baseObj) {
    // The public function name defaults to window.docReady
    // but you can pass in your own object and own function name and those will be used
    // if you want to put them in a different namespace
    funcName = funcName || "docReady";
    baseObj = baseObj || window;
    var readyList = [];
    var readyFired = false;
    var readyEventHandlersInstalled = false;

    // call this when the document is ready
    // this function protects itself against being called more than once
    function ready() {
        if (!readyFired) {
            // this must be set to true before we start calling callbacks
            readyFired = true;
            for (var i = 0; i < readyList.length; i++) {
                // if a callback here happens to add new ready handlers,
                // the docReady() function will see that it already fired
                // and will schedule the callback to run right after
                // this event loop finishes so all handlers will still execute
                // in order and no new ones will be added to the readyList
                // while we are processing the list
                readyList[i].fn.call(window, readyList[i].ctx);
            }
            // allow any closures held by these functions to free
            readyList = [];
        }
    }

    function readyStateChange() {
        if ( document.readyState === "complete" ) {
            ready();
        }
    }

    // This is the one public interface
    // docReady(fn, context);
    // the context argument is optional - if present, it will be passed
    // as an argument to the callback
    baseObj[funcName] = function(callback, context) {
        // if ready has already fired, then just schedule the callback
        // to fire asynchronously, but right away
        if (readyFired) {
            setTimeout(function() {callback(context);}, 1);
            return;
        } else {
            // add the function and context to the list
            readyList.push({fn: callback, ctx: context});
        }
        // if document already ready to go, schedule the ready function to run
        if (document.readyState === "complete") {
            setTimeout(ready, 1);
        } else if (!readyEventHandlersInstalled) {
            // otherwise if we don't have event handlers installed, install them
            if (document.addEventListener) {
                // first choice is DOMContentLoaded event
                document.addEventListener("DOMContentLoaded", ready, false);
                // backup is window load event
                window.addEventListener("load", ready, false);
            } else {
                // must be IE
                document.attachEvent("onreadystatechange", readyStateChange);
                window.attachEvent("onload", ready);
            }
            readyEventHandlersInstalled = true;
        }
    }
})("docReady", window);

docReady(miCheckjQuery);

function miCheckjQuery(){
  if(typeof jQuery!='undefined') {
    mi_init(jQuery);
  }else{
    miGetjQuery('http://code.jquery.com/jquery.min.js', mi_init);
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
    $(document).bind('contextmenu', function(e) {
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