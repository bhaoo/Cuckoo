<?php

/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Sidebar
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 1.0.5
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>
<div class="right">
  <div class="mdui-card sidebar-info mdui-shadow-10">
    <div class="sidebar-info-img">
      <img class="mdui-img-circle" style="width: 80px;" src="<?php logo(); ?>" />
    </div>
    <div class="sidebar-info-body">
      <?php describe(); ?>
    </div>
  </div>
  <div class="mdui-card sidebar-info mdui-shadow-10">
    <ul class="mdui-list">
      <div class="sidebar-reply-title">最新回复</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
      <?php while ($comments->next()) : if (!$this->hidden) { ?>
          <a href="<?php $comments->permalink(); ?>">
            <li class="mdui-list-item mdui-ripple sidebar-reply-list">
              <div class="sidebar-reply-text"><?php $comments->author(false); ?> : <?php $comments->excerpt(); ?></div>
            </li>
            <li class="mdui-divider"></li>
          </a>
      <?php }
      endwhile; ?>
    </ul>
  </div>
  <?php if ($this->options->tagCloud != "0") { ?>
  <div class="mdui-card sidebar-info mdui-shadow-10">
    <ul class="mdui-list">
      <div class="sidebar-reply-title">标签云</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <div class="tag-cloud">
        <?php $this->widget('Widget_Metas_Tag_Cloud', 'sort=mid&desc=0&limit='.$this->options->tagCloud)->to($tags); ?>
        <?php if ($tags->have()) : ?>
          <?php while ($tags->next()) : ?>
            <a href="<?php $tags->permalink(); ?>">
              <div class="mdui-chip">
                <span class="mdui-chip-title"><?php $tags->name(); ?></span>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else : ?>
          <li><?php _e('没有任何标签'); ?></li>
        <?php endif; ?>
      </div>
    </ul>
  </div>
  <?php } if ($this->options->linksIndexNum != "0") { ?>
    <div class="mdui-card sidebar-info mdui-shadow-10">
      <ul class="mdui-list">
        <div class="sidebar-reply-title">友情链接</div>
        <li class="mdui-divider mdui-m-y-0"></li>
        <div class='mdui-row-xs-2'>
          <?php Links_Plugin::output("
        <div class='mdui-col'>
        <a href='{url}'>
         <li class='mdui-list-item mdui-ripple sidebar-reply-list'>
            <div class='sidebar-reply-text'>{name}</div>
          </li>    
        </a>
        </div>
        ", $this->options->linksIndexNum); ?>
        </div>
      </ul>
    </div>
  <?php } ?>
  <?php if ($this->is('single') && $this->fields->catalog == "true" && !$this->hidden) { ?>
    <div class="sidebar-info mdui-shadow-10" id="toc">
      <ul class="mdui-list">
        <div class="sidebar-reply-title">文章目录</div>
        <li class="mdui-divider"></li>
        <div class="toc"></div>
      </ul>
    </div>
  <?php } ?>
</div>