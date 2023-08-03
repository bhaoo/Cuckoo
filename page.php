<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * Page
 *
 * @author Bhao
 * @link https://dwd.moe/
 * @version 2.0.0
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('includes/header.php'); ?>
<div class="index-container">
  <div class="mdui-col-md-8">
    <div class="mdui-card post-card">
      <div class="mdui-card-media post-card-media<?php if ($this->fields->articleType == "normal") echo ' post-card-media-normal';?>">
        <?php if($this->fields->articleType != "normal") { ?>
          <div class="index-img" data-bg="<?php $wzimg = $this->fields->wzimg;if (!empty($wzimg)) {echo $wzimg;} else {echo randPic();} ?>"></div>
        <?php } ?>
        <div class="mdui-card-media-covered">
          <div class="mdui-card-primary">
            <div class="mdui-card-primary-title"><?php $this->title() ?></div>
            <div class="mdui-card-primary-subtitle"><?php get_post_view($this) ?> 浏览 | <?php $this->date(); ?> | 阅读时间: <?php readingTime($this->cid); ?> | 分类: <?php $this->category(','); ?> | 标签: <?php $this->tags(','); ?></div>
          </div>
        </div>
      </div>
      <div class="post-content mdui-typo">
        <?php parseContent($this->content); ?>
      </div>
    </div>
    <?php if ($this->options->showComments) $this->need('includes/comments.php'); ?>
  </div>
  <?php $this -> need('includes/sidebar.php'); ?>
</div>
<?php $this -> need('includes/footer.php'); ?>


