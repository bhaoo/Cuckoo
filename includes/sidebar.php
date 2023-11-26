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
 * @date 2023-08-02
 */

if(!defined('__TYPECHO_ROOT_DIR__'))
  exit;
?>
<div class="mdui-col-md-4">
  <div class="mdui-card mdui-hoverable sidebar-info">
    <div class="sidebar-info-img">
      <div class="sidebar-info-bg"
           style="background-image: url('<?php setting("sidebarBg", "images/sidebar.jpg"); ?>')"></div>
      <div class="mdui-img-circle mdui-shadow-3"
           style="background-image: url('<?php setting("logoUrl", "images/logo.jpg"); ?>')"></div>
    </div>
    <div class="sidebar-info-body">
      <div class="sidebar-info-name"><?php echo Helper::options()->title ?></div>
      <div class="sidebar-info-desc"><?php setting("describe", "<span id='hitokoto'>:D 获取中...</span>", 1); ?></div>
    </div>
  </div>
  <?php if ($this->options->showComments) { ?>
  <div class="mdui-card mdui-hoverable sidebar-module">
    <ul class="mdui-list">
      <div class="sidebar-module-title">最新回复</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <?php $this -> widget('Widget_Comments_Recent') -> to($comments); ?>
      <?php while($comments -> next()) : if(!$this -> hidden){ ?>
        <a href="<?php $comments -> permalink(); ?>">
          <li class="mdui-list-item mdui-ripple sidebar-module-list">
            <div class="sidebar-reply-text"><?php $comments -> author(false); ?>
              : <?php $comments -> excerpt(); ?></div>
          </li>
          <li class="mdui-divider"></li>
        </a>
      <?php }
      endwhile; ?>
    </ul>
  </div>
  <?php } if ($this->options->tagCloud != "0") { ?>
  <div class="mdui-card mdui-hoverable sidebar-module">
    <ul class="mdui-list">
      <div class="sidebar-module-title">标签云</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <div class="sidebar-tag">
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
  <?php } if (array_key_exists("Links", Typecho_Plugin::export()['activated']) && $this->options->linksIndexNum != "0") { ?>
  <div class="mdui-card mdui-hoverable sidebar-module">
    <ul class="mdui-list">
      <div class="sidebar-module-title">友情链接</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <div class='mdui-row-xs-2'>
        <?php Links(1); ?>
      </div>
    </ul>
  </div>
  <?php } ?>
  <?php if ($this->is('single') && $this->fields->catalog == "true" && !$this->hidden) { ?>
  <div class="mdui-card mdui-hoverable sidebar-module" id="toc">
    <ul class="mdui-list">
      <div class="sidebar-module-title">文章目录</div>
      <li class="mdui-divider"></li>
      <div class="toc"></div>
    </ul>
  </div>
  <?php } ?>
</div>
<span class="mdui-text-color-theme"></span>
