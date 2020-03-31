<?php 
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Header
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 1.0.3
 */

$primaryColor = $this->options->primaryColor;
$accentColor = $this->options->accentColor;
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
  <title><?php $this->archiveTitle(array('category' => _t('分类 %s 下的文章'), 'search' => _t('包含关键字 %s 的文章'), 'tag' => _t('标签 %s 下的文章'), 'author' => _t('%s 发布的文章')), '', ' - ');$this->options->title(); ?></title>
  <link rel="shortcut icon" href="<?php favicon() ?>" /> 
  <link rel="stylesheet" href="<?php staticFiles('assets/css/mdui.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/iconfont.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/tocbot.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/cuckoo.min.css'); ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/nprogress.min.css'); ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/atom-one-dark.min.css'); ?>">
  <script src="<?php staticFiles('assets/js/nprogress.min.js') ?>"></script>
  <script src="<?php staticFiles('assets/js/tocbot.min.js') ?>"></script>
  <?php fontFamily();$this->header('commentReply='); bgUrl(); ?>
</head>

<body class="mdui-theme-primary-<?php echo $primaryColor; ?> mdui-theme-accent-<?php echo $accentColor; ?> mdui-appbar-with-toolbar " id="body">
  <div class="background"></div>
  <div class="mdui-appbar mdui-shadow-0 mdui-appbar-fixed mdui-appbar-scroll-hide">
    <div class="mdui-toolbar">
      <a class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#menu', swipe: 'true', overlay: 'false'}">
        <i class="mdui-icon material-icons">menu</i>
      </a>
      <a href="<?php Helper::options()->siteUrl() ?>" class="mdui-typo-title"><?php $this->options->title(); ?></a>
      <div class="mdui-toolbar-spacer"></div>
      <div class="mdui-textfield mdui-textfield-expandable mdui-float-right search">
        <button class="mdui-textfield-icon mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">search</i></button>
        <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
          <input class="mdui-textfield-input" type="text" id="s" name="s" placeholder="搜索一下" />
          <button type="submit" style="display:none;"><?php _e('搜索'); ?></button>
        </form>
        <button class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">close</i></button>
      </div>
      <button onclick="brightness()" id="brightness" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">brightness_5</i></button>
      <button onclick="tocBotton()" id="tocBotton" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">bookmark</i></button>
    </div>
  </div>
  <div class="mdui-drawer mdui-drawer-close mdui-drawer-full-height" id="menu">
    <div class="drawer-img"><img class="mdui-img-circle" src="<?php logo(); ?>" /></div>
    <div class="drawer-contact">
      <?php contact(); ?>
    </div>
    <div class="mdui-divider"></div>
    <ul class="mdui-list" mdui-collapse="{accordion: true}">
      <a no-go href="<?php Helper::options()->siteUrl() ?>">
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">home</i>
          <div class="mdui-list-item-content">首页</div>
        </li>
      </a>
      <?php otherMenu(); ?>
    </ul>
  </div>