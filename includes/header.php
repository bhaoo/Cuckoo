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
 * @version 2.0.3
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$primaryColor = $this->options->primaryColor;
$accentColor = $this->options->accentColor;
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
  <?php if ($this->is('index')): ?>
    <meta property="og:type" content="blog"/>
    <meta property="og:url" content="<?php $this->options->siteUrl();?>"/>
    <meta property="og:title" content="<?php $this->options->webTitle();?>"/>
    <meta property="og:image" content="<?php $this->options->logoUrl();?>"/>
    <meta property="og:author" content="<?php $this->author->name();?>"/>
    <meta name="keywords"  content="<?php $this->keywords();?>">
    <meta name="description"  content="<?php $this->options->description();?>">
  <?php endif;?>
  <?php if ($this->is('post') || $this->is('page') || $this->is('attachment')): ?>
    <meta property="og:url" content="<?php $this->permalink();?>"/>
    <meta property="og:title" content="<?php $this->title();?> - <?php $this->options->title();?>"/>
    <meta property="og:author" content="<?php $this->author();?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:image" content="<?php $wzimg = $this->fields->wzimg; echo empty($wzimg) ? randPic() : $wzimg; ?>"/>
    <meta property="og:description" content="<?php $this->excerpt(); ?>"/>
    <meta property="article:published_time" content="<?php $this->date('c'); ?>"/>
    <meta property="article:published_first" content="<?php $this->options->title() ?>, <?php $this->permalink() ?>" />
    <meta name="keywords"  content="<?php $k=$this->fields->keyword;if(empty($k)){echo $this->keywords();}else{ echo $k;};?>">
    <meta name="description" content="<?php $d=$this->fields->description;if(empty($d) || !$this->is('single')){if($this->getDescription()){echo $this->getDescription();}}else{ echo $d;};?>" />
  <?php endif;?>
  <title><?php $this->archiveTitle(array('category' => _t('分类 %s 下的文章'), 'search' => _t('包含关键字 %s 的文章'), 'tag' => _t('标签 %s 下的文章'), 'author' => _t('%s 发布的文章')), '', ' - ');$this->options->webTitle(); ?></title>
  <link rel="shortcut icon" href="<?php setting("favicon", "images/favicon.ico"); ?>" />
  <link rel="stylesheet" href="<?php staticFiles('css/mdui.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('css/atom-one-dark.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('css/iconfont.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('css/tocbot.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('css/fancybox.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('css/katex.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('css/cuckoo.min.css') ?>">
  <script src="<?php staticFiles('js/tocbot.min.js') ?>"></script>
  <script src="<?php staticFiles('js/nprogress.min.js') ?>"></script>
  <?php fontFamily(); $this->header('antiSpam=&commentReply='); bgUrl(); otherCss();?>
</head>

<body class="mdui-theme-primary-<?php echo $primaryColor; ?> mdui-theme-accent-<?php echo $accentColor; ?>">
  <div class="background"><div class="index-filter"></div></div>

  <div class="mdui-appbar mdui-shadow-0 mdui-appbar-fixed mdui-appbar-scroll-hide">
    <div class="mdui-toolbar">
      <a class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '.drawer', swipe: 'true', overlay: 'false'}">
        <i class="mdui-icon material-icons">menu</i>
      </a>
      <a href="<?php Helper::options()->siteUrl() ?>" class="mdui-typo-title"><?php $this->options->webTitle(); ?></a>
      <div class="mdui-toolbar-spacer"></div>
      <div class="mdui-textfield mdui-textfield-expandable mdui-float-right appbar-search">
        <button class="mdui-textfield-icon mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">search</i></button>
        <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
          <input class="mdui-textfield-input" type="text" id="s" name="s" placeholder="搜索一下" />
          <button type="submit" style="display:none;"><?php _e('搜索'); ?></button>
        </form>
        <button class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">close</i></button>
      </div>
      <button class="mdui-btn mdui-btn-icon qrcode" mdui-menu="{target: '#qrcode'}" mdui-tooltip="{content: '跨设备阅读'}"><i class="mdui-icon material-icons">devices</i></button>
      <div class="mdui-menu" id="qrcode" style="overflow-y: hidden;width: 170px;height: 170px;transform-origin: 100% 0px; position: fixed; text-align:center;"><div style='margin-top: 63px;' class="mdui-spinner mdui-spinner-colorful"></div></div>
      <button onclick="brightness()" id="brightness" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">brightness_5</i></button>
      <button onclick="tocBotton()" id="tocBotton" class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">bookmark</i></button>
    </div>
  </div>
  <div class="mdui-drawer mdui-drawer-close mdui-drawer-full-height drawer">
    <div class="drawer-img"><img class="mdui-img-circle" src="<?php setting("logoUrl", "images/logo.jpg"); ?>" /></div>
    <div class="drawer-contact">
      <?php contact() ?>
    </div>
    <div class="mdui-divider"></div>
    <ul class="mdui-list drawer-list" mdui-collapse="{accordion: true}">
      <a href="<?php Helper::options()->siteUrl() ?>" mdui-drawer-close>
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">home</i>
          <div class="mdui-list-item-content">首页</div>
        </li>
      </a>
      <?php otherMenu(); ?>
    </ul>
    <?php drawerBottom() ?>
  </div>