<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
<link rel="stylesheet" href="<?php staticFiles('css/mdui.min.css') ?>">
<link rel="stylesheet" href="<?php staticFiles('css/setting.min.css') ?>" />
<style>
  .background{background-image: url("<?php setting("bgUrl", "images/bg.jpg"); ?>");}
  .setting-card-img{background-image: url("<?php staticFiles('images/yousa.png'); ?>");}
</style>

<body class="mdui-theme-primary-<?php echo Helper::options()->primaryColor; ?> mdui-theme-accent-<?php echo Helper::options()->accentColor; ?>">
<div class="background"><div class="index-filter"></div></div>
<div class="mdui-appbar mdui-shadow-0 mdui-appbar-fixed">
  <div class="mdui-toolbar">
    <a class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '.drawer', swipe: 'true', overlay: 'false'}">
      <i class="mdui-icon material-icons">menu</i>
    </a>
    <a class="mdui-typo-title">Cuckoo</a>
    <div class="mdui-toolbar-spacer"></div>
  </div>
</div>
<div class="mdui-drawer mdui-drawer-close mdui-drawer-full-height drawer">
  <div class="drawer-img"><img class="mdui-img-circle" src="<?php setting("logoUrl", "images/logo.jpg"); ?>" /></div>
  <div class="drawer-contact">
    <button onclick="brightness()" id="brightness" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">brightness_5</i></button>
  </div>
  <div class="mdui-divider"></div>
  <ul class="mdui-list drawer-list" mdui-collapse="{accordion: true}">
    <a href="<?php Helper::options()->siteUrl() ?>">
      <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">home</i>
        <div class="mdui-list-item-content">博客主页</div>
      </li>
    </a>
    <a href="//cuckoo.owo.show">
      <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">book</i>
        <div class="mdui-list-item-content">主题文档</div>
      </li>
    </a>
    <a href="<?php Helper::options()->adminUrl ?>themes.php">
      <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">list</i>
        <div class="mdui-list-item-content">主题列表</div>
      </li>
    </a>
    <a target="_blank" href="<?php Helper::options()->adminUrl() ?>">
      <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">home</i>
        <div class="mdui-list-item-content">后台首页</div>
      </li>
    </a>
    <div class="mdui-divider"></div>
    <div mdui-tab>
      <a href="#message" mdui-drawer-close>
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">message</i>
          <div class="mdui-list-item-content">基础信息</div>
        </li>
      </a>
      <a href="#basic" mdui-drawer-close>
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">settings</i>
          <div class="mdui-list-item-content">基础设置</div>
        </li>
      </a>
      <a href="#menu" mdui-drawer-close>
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">settings</i>
          <div class="mdui-list-item-content">更多设置</div>
        </li>
      </a>
      <a href="#advanced" mdui-drawer-close>
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">settings</i>
          <div class="mdui-list-item-content">高级设置</div>
        </li>
      </a>
      <a href="#plugins" mdui-drawer-close>
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">dns</i>
          <div class="mdui-list-item-content">插件扩展</div>
        </li>
      </a>
    </div>
  </ul>
  <div class="drawer-bottom mdui-typo">
    <div>Powered by <a href="http://www.typecho.org" target="_blank">Typecho</a></div>
    <div>Theme <a href="https://github.com/bhaoo/Cuckoo" target="_blank">Cuckoo</a> By <a href="https://dwd.moe" target="_blank">Bhao</a></div>
  </div>
</div>
<div class="setting-container">
  <div class="setting-card">
    <div class="setting-card-img"></div>
    <div class="mdui-card-media-covered">
      <div class="mdui-card-primary">
        <div class="mdui-card-primary-title">欢迎使用「Cuckoo」主题</div>
      </div>
    </div>
  </div>