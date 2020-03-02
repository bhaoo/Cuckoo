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
 * @version 1.0.0
 */
?>
<div class="right">
  <div class="sidebar-info mdui-shadow-10">
    <div class="sidebar-info-img">
      <img class="mdui-img-circle" style="width: 80px;" src="<?php logo(); ?>" />
    </div>
    <div class="sidebar-info-body">
      <?php describe(); ?>
    </div>
  </div>
  <?php if ($this->is('page', $this->options->linksCid) && $this->options->linksDescribe != NULL): ?>
  <div class="sidebar-info mdui-shadow-10">
     <ul class="mdui-list">
      <div class="sidebar-reply-title">个人介绍</div>
      <li class="mdui-divider mdui-m-y-0"></li>
        <li class="links-list">
          <div class="links-text"><?php linksDescribe(); ?></div>
        </li>
    </ul>
  </div>
  <?php endif; ?>
   <?php $this->related(5)->to($relatedPosts); ?>
   <?php if($relatedPosts->have()): ?>
   <div class="sidebar-info mdui-shadow-10">
      <ul class="mdui-list">
        <div class="sidebar-reply-title">相关文章</div>
        <li class="mdui-divider"></li>
         <?php while ($relatedPosts->next()): ?>
        	<a title="<?php $relatedPosts->title(); ?>" href="<?php $relatedPosts->permalink(); ?>">
        	 <li class="mdui-list-item mdui-ripple sidebar-reply-list">
        	 <div class="sidebar-reply-text"><?php $relatedPosts->title(); ?></div>
        	 </li>
        	  <li class="mdui-divider"></li>
          <?php endwhile; ?>
        </a>
      </ul>
    </div>
    <?php endif; ?>
  <?php Typecho_Widget::widget('Widget_Metas_Tag_Cloud', array('sort' => 'count', 'ignoreZeroCount' => true, 'desc' => true))->to($tags); ?>
  <?php if($tags->have()): ?>
  <div class="sidebar-info mdui-shadow-10">
    <ul class="mdui-list">
      <div class="sidebar-reply-title">标签云</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <li style="padding:5px">
      <?php if($tags->have()): ?>
        <?php while ($tags->next()): ?>
        <div class="mdui-chip mdui-color-theme-accent"><span class="mdui-chip-title"><a href="<?php $tags->permalink();?>">
           <?php $tags->name(); ?></a></span></div>
    	<?php endwhile; ?>
    	<?php endif; ?>
      </li>
    </ul>
  </div>
  <? endif; ?>
  <?php $this->widget('Widget_Comments_Recent')->to($comments); ?>
  <?php if($comments->have()): ?>
  <div class="sidebar-info mdui-shadow-10">
    <ul class="mdui-list">
      <div class="sidebar-reply-title">最新回复</div>
      <li class="mdui-divider mdui-m-y-0"></li>
      <?php while ($comments->next()) : ?>
        <a href="<?php $comments->permalink(); ?>">
          <li class="mdui-list-item mdui-ripple sidebar-reply-list">
            <div class="sidebar-reply-text"><?php $comments->author(false); ?> : <?php $comments->excerpt(); ?></div>
          </li>
          <li class="mdui-divider"></li>
        </a>
      <?php endwhile; ?>
    </ul>
  </div>
  <?php endif; ?>
  <?php if($this->options->linksIndexNum != "0"){?>
  <div class="sidebar-info mdui-shadow-10">
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
        ", $this->options->linksIndexNum);?>
      </div>
    </ul>
  </div>
  <?php } ?>
  <?php if ($this->is('single')) : 
        if ($this->fields->catalog == "true") {?>
    <div class="sidebar-info mdui-shadow-10" id="toc">
      <ul class="mdui-list">
        <div class="sidebar-reply-title">文章目录</div>
        <li class="mdui-divider"></li>
        <div class="toc"></div>
      </ul>
    </div>
  <?php } endif; ?>
</div>
