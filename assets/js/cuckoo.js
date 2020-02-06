$(document).pjax('a[href^="<?php Helper::options()->siteUrl() ?>"]:not(a[target="_blank"], a[no-pjax])', {
  container: '.body',
  fragment: '.body',
  timeout: 8000
}).on('pjax:send',
  function () {
    if ($('.toc').length) tocbot.destroy();
  }).on('pjax:complete',
  function () {
    toc.init(tocOptions);
  });

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
jQuery(".article-pic").lazyload({
  threshold: 100,
  effect: "fadeIn"
});
jQuery(".page-img").lazyload({
  threshold: 100,
  effect: "fadeIn"
});
