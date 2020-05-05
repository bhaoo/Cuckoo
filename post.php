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
 * @version 1.0.5
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('includes/header.php'); ?>
<div class="container">
  <?php $this->need('includes/sidebar.php'); ?>
  <div class="left">
    <div class="mdui-card page-card">
      <?php if ($this->fields->articleType == "daily") { ?>
        <div class="daily-media">
          <div class="daily-card-title"><?php $this->title() ?></div>
          <div class="mdui-card-primary-subtitle diary-subtitle"><?php get_post_view($this) ?> 浏览 | <?php $this->date(); ?> | 阅读时间: <?php readingTime($this->cid); ?> | 分类: <?php $this->category(','); ?> | 标签: <?php $this->tags(','); ?></div>
        </div>
      <?php } elseif ($this->fields->articleType == "article" or $this->fields->articleType == NULL) { ?>
        <div class="mdui-card-media">
          <div class="article-pic" data-original="<?php $wzimg = $this->fields->wzimg;
                                                  if (!empty($wzimg)) {
                                                    echo $wzimg;
                                                  } else {
                                                    echo randPic();
                                                  } ?>"></div>
          <div class="mdui-card-media-covered">
            <div class="mdui-card-primary">
              <div class="mdui-card-primary-title"><?php $this->title() ?></div>
              <div class="mdui-card-primary-subtitle article-subtitle"><?php get_post_view($this) ?> 浏览 | <?php $this->date(); ?> | 阅读时间: <?php readingTime($this->cid); ?> | 分类: <?php $this->category(','); ?> | 标签: <?php $this->tags(','); ?></div>
            </div>
          </div>
        </div>
      <?php } ?>
      <div class="article-page mdui-typo">
      <?php $modified = intval((time() - $this->modified) / 86400);
            $created = intval((time() - $this->created) / 86400);
            if($modified > 30){echo "<div class='warning'><blockquote>请注意，本文编写于 $created 天前，最后修改于 $modified 天前，其中某些信息可能已经过时。</blockquote></div>";}
            echo parseBiaoQing(parseContent($this->content)); ?>
        <div class="article-copy">
          <?php echo $this->options->articleCopy ?>
        </div>
      </div>
    </div>
    <?php if(!$this->hidden){$this->need('includes/comments.php');} ?>
  </div>
</div>
<?php $this->need('includes/footer.php'); ?>