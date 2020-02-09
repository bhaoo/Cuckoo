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
 * @version 0.0.2(Beta)
 */
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
  <title><?php $this->archiveTitle(array('category' => _t('分类 %s 下的文章'), 'search' => _t('包含关键字 %s 的文章'), 'tag' => _t('标签 %s 下的文章'), 'author' => _t('%s 发布的文章')), '', ' - ');$this->options->title(); ?></title>
  <link rel="shortcut icon" href="<?php favicon() ?>" /> 
  <link rel="stylesheet" href="<?php staticFiles('assets/css/mdui.min.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/iconfont.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/tocbot.css') ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/cuckoo.css'); ?>">
  <link rel="stylesheet" href="<?php staticFiles('assets/css/nprogress.css'); ?>">
  <script src="<?php staticFiles('assets/js/nprogress.js') ?>"></script>
  <script src="<?php staticFiles('assets/js/tocbot.min.js') ?>"></script>
  <?php $this->header(); bgUrl(); ?>
</head>

<body class="body" id="body">
  <div class="mdui-appbar mdui-shadow-0">
    <div class="mdui-toolbar">
      <a class="mdui-btn mdui-btn-icon" mdui-drawer="{target: '#menu', swipe: 'true', overlay: 'false'}">
        <i class="mdui-icon material-icons">menu</i>
      </a>
      <a href="<?php Helper::options()->siteUrl() ?>" class="mdui-typo-title"><?php $this->options->title(); ?></a>
      <div class="mdui-toolbar-spacer"></div>
      <div class="mdui-textfield mdui-textfield-expandable mdui-float-right">
        <button class="mdui-textfield-icon mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">search</i></button>
        <form id="search" method="post" action="<?php $this->options->siteUrl(); ?>" role="search">
          <input class="mdui-textfield-input" type="text" id="s" name="s" placeholder="搜索一下" />
          <button type="submit" style="display:none;"><?php _e('搜索'); ?></button>
        </form>
        <button class="mdui-textfield-close mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">close</i></button>
      </div>
    </div>
  </div>
  <div class="mdui-drawer mdui-drawer-close mdui-color-white" id="menu">
    <div class="drawer-img"><img class="mdui-img-circle" src="<?php logo(); ?>" /></div>
    <div class="drawer-contact">
      <?php contact(); ?>
    </div>
    <div class="mdui-divider"></div>
    <ul class="mdui-list" mdui-collapse="{accordion: true}">
      <a href="/index.php">
        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">home</i>
          <div class="mdui-list-item-content">首页</div>
        </li>
      </a>
      <div class="mdui-collapse" mdui-collapse>
        <li class="mdui-collapse-item">
          <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">view_list</i>
            <div class="mdui-list-item-content">分类</div>
            <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
          </div>
          <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
            <?php $this->widget('Widget_Metas_Category_List')->to($categories); ?>
            <?php while ($categories->next()) : ?>
              <a href="<?php $categories->permalink(); ?>" rel="section">
                <li class="mdui-list-item mdui-ripple">
                  <div class="mdui-list-item-content"><?php $categories->name(); ?></div>
                  <div class="drawer-item"><?php $categories->count(); ?></div>
                </li>
              </a>
            <?php endwhile; ?>
          </ul>
        </li>
      </div>
      <div class="mdui-collapse" mdui-collapse>
        <li class="mdui-collapse-item">
          <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">access_time</i>
            <div class="mdui-list-item-content">归档</div>
            <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
          </div>
          <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
            <?php $this->widget('Widget_Contents_Post_Date', 'type=month&format=Y 年 m 月')
              ->parse('<a href="{permalink}"><li class="mdui-list-item mdui-ripple"><div class="mdui-list-item-content">{date}</div><div class="drawer-item">{count}</div></li></a>'); ?>
          </ul>
        </li>
      </div>
      <div class="mdui-collapse" mdui-collapse>
        <li class="mdui-collapse-item">
          <div class="mdui-collapse-item-header mdui-list-item mdui-ripple">
            <i class="mdui-list-item-icon mdui-icon material-icons">view_carousel</i>
            <div class="mdui-list-item-content">页面</div>
            <i class="mdui-collapse-item-arrow mdui-icon material-icons">keyboard_arrow_down</i>
          </div>
          <ul class="mdui-collapse-item-body mdui-list mdui-list-dense">
            <?php Typecho_Widget::widget('Widget_Contents_Page_List')->to($pages);
            while ($pages->next()) {
              echo '<a href="' . $pages->permalink . '"><li class="mdui-list-item mdui-ripple"><div class="mdui-list-item-content">' . $pages->title . '</div></li></a>';
            }
            ?>
          </ul>
        </li>
      </div>
      <?php links(); ?>
      <div class="mdui-divider"></div>
      <a href="<?php $this->options->feedUrl(); ?>">
        <li class="mdui-list-item mdui-ripple">
          <i class="mdui-list-item-icon mdui-icon material-icons">rss_feed</i>
          <div class="mdui-list-item-content">RSS 订阅</div>
        </li>
      </a>
    </ul>
  </div>