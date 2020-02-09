/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Style
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 0.0.2(Beta)
 */

var ias = jQuery.ias({
  container: ".left",
  item: ".page",
  pagination: ".changePage",
  next: ".next"
});
ias.extension(new IASTriggerExtension({
  text: "加载更多",
  offset: 2,
}));
ias.extension(new IASSpinnerExtension({
  html: '<div class="mdui-spinner mdui-spinner-colorful"></div>'
}));
ias.extension(new IASNoneLeftExtension({
  text: "到底了啦"
}));
ias.on("rendered", function (items) {

});

$(function () {
  $(".top")
    .hide();
  $(function () {
    $(window)
      .scroll(function () {
        if ($(window)
          .scrollTop() > 300) {
          $(".top")
            .fadeIn(300)
        } else {
          $(".top")
            .fadeOut(200)
        }
      });
    $(".top")
      .click(function () {
        $("body,html")
          .animate({
            scrollTop: 0
          }, 300);
        return false
      })
  })
});

$("#comment-form").submit(function () {
  $('#submit').attr("disabled", true);
  var data = $("#comment-form").serialize();
  $.ajax({
    type: "POST",
    url: $(this).attr('action'),
    data: data,
    success: function success(back) {
      var backC = back;
      var $$ = mdui.JQ;
      var Obj = $("<body></body>").append($(backC));
      var $html = $(".containe", Obj);
      if ($html.html() == null) {
        $.pjax.reload({
          container: '#comment-list',
          fragment: '#comment-list',
          timeout: 3000
        });
        $('#submit').attr("disabled", false);
        mdui.snackbar({
          message: '评论成功！',
          position: 'right-bottom'
        });
      } else {
        $('#submit').attr("disabled", false);
        mdui.snackbar({
          message: $html.html(),
          position: 'right-bottom'
        });
      }
    }
  });
  return false;
});
if ($('.toc').length > 0) {
  var headerEl = 'h1,h2,h3,h4',
    content = '.article-page',
    idArr = {};
  $(content).children(headerEl).each(function () {
    var headerId = $(this).text().replace(/[\s|\~|`|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\+|\=|\||\|\[|\]|\{|\}|\;|\:|\"|\'|\,|\<|\.|\>|\/|\?|\：|\，|\。]/g, '');
    headerId = headerId.toLowerCase();
    if (idArr[headerId]) {
      $(this).attr('id', headerId + '-' + idArr[headerId]);
      idArr[headerId]++;
    } else {
      idArr[headerId] = 1;
      $(this).attr('id', headerId);
    }
  });

  tocbot.init({
    tocSelector: '.toc',
    contentSelector: content,
    headingSelector: headerEl,
    positionFixedSelector: '#toc',
    positionFixedClass: 'is-position-fixed',
    scrollSmooth: true,
    scrollSmoothOffset: -80,
    headingsOffset: -50,
  });
}
jQuery(".article-pic").lazyload({
  threshold: 200,
  effect: "fadeIn"
});
jQuery(".page-img").lazyload({
  threshold: 200,
  effect: "fadeIn"
});
$(document).pjax('a[href^="'+document.location.protocol+'//'+window.location.host+'/"]:not(a[target="_blank"], a[no-pjax])', {
  container: '.container',
  fragment: '.container',
  timeout: 8000
}).on('pjax:send',
  function () {
    if ($('.toc').length) tocbot.destroy();
    NProgress.start();
  }).on('pjax:complete',
  function () {
    NProgress.done();
    jQuery(".article-pic").lazyload({
      threshold: 200,
      effect: "fadeIn"
    });
    jQuery(".page-img").lazyload({
      threshold: 200,
      effect: "fadeIn"
    });
    if ($('.toc').length > 0) {
      var headerEl = 'h1,h2,h3,h4',
        content = '.article-page',
        idArr = {};
      $(content).children(headerEl).each(function () {
        var headerId = $(this).text().replace(/[\s|\~|`|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\+|\=|\||\|\[|\]|\{|\}|\;|\:|\"|\'|\,|\<|\.|\>|\/|\?|\：|\，|\。]/g, '');
        headerId = headerId.toLowerCase();
        if (idArr[headerId]) {
          $(this).attr('id', headerId + '-' + idArr[headerId]);
          idArr[headerId]++;
        } else {
          idArr[headerId] = 1;
          $(this).attr('id', headerId);
        }
      });
    
      tocbot.init({
        tocSelector: '.toc',
        contentSelector: content,
        headingSelector: headerEl,
        positionFixedSelector: '#toc',
        positionFixedClass: 'is-position-fixed',
        scrollSmooth: true,
        scrollSmoothOffset: -80,
        headingsOffset: -50,
      });
    }
  });

  Smilies = {
    domId: function (id) {
      return document.getElementById(id)
    },
    domTag: function (id) {
      return document.getElementsByTagName(id)[0]
    },
    grin: function (tag) {
      tag = ' ' + tag + ' ';
      myField = this.domId("comment-textarea");
      document.selection ? (myField.focus(), sel = document.selection.createRange(), sel.text = tag, myField.focus()) : this.insertTag(tag)
    },
    insertTag: function (tag) {
      myField = Smilies.domId("comment-textarea");
      myField.selectionStart || myField.selectionStart == "0" ? (startPos = myField.selectionStart, endPos = myField.selectionEnd, cursorPos = startPos, myField.value = myField.value.substring(0, startPos) + tag + myField.value.substring(endPos, myField.value.length), cursorPos += tag.length, myField.focus(), myField.selectionStart = cursorPos, myField.selectionEnd = cursorPos) : (myField.value += tag, myField.focus())
    }
  };