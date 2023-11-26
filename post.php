<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * Post
 *
 * @author Bhao
 * @link https://dwd.moe/
 * @date 2023-10-28
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
if (@$_SERVER['HTTP_X_PJAX'] == true) {
    Typecho_Response::getInstance()->setStatus(200);
}
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
      <?php
        $modified = intval((time() - $this->modified) / 86400);
        $created = intval((time() - $this->created) / 86400);
        if($modified > 30){echo '<div class="post-alert mdui-color-red-400 mdui-valign"><i class="mdui-icon material-icons mdui-m-r-2">report</i>请注意，本文编写于 '.$created.' 天前，最后修改于 '.$modified.' 天前，其中某些信息可能已经过时。</div>';}
      ?>
      <div class="post-content mdui-typo">
        <?php parseContent($this->content); ?>
      </div>
    </div>
    <?php if ($this->options->showComments) $this->need('includes/comments.php'); ?>
  </div>
  <?php $this -> need('includes/sidebar.php'); ?>
</div>
<?php $this -> need('includes/footer.php'); ?>

