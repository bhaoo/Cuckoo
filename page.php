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
 * @version 0.0.5(Beta)
 */

$this->need('includes/header.php'); ?>
<div class="container">
  <?php $this->need('includes/sidebar.php'); ?>
  <div class="left">
    <div class="mdui-card page-card">
      <?php if ($this->fields->articleType == "daily") { ?>
        <div class="daily-media">
          <div class="daily-card-title"><?php $this->title() ?></div>
          <div class="mdui-card-primary-subtitle daily-card-subtitle"><?php get_post_view($this) ?> 浏览 | <?php $this->date(); ?> | 分类：<?php $this->category(','); ?> | 阅读时间：约<?php art_count($this->cid); ?>分钟</div>
        </div>
      <?php } elseif ($this->fields->articleType == "article") { ?>
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
              <div class="mdui-card-primary-subtitle article-subtitle"><?php get_post_view($this) ?> 浏览 | <?php $this->date(); ?> | 分类：<?php $this->category(','); ?> | 阅读时间：约<?php art_count($this->cid); ?>分钟</div>
            </div>
          </div>
        </div>
      <?php } ?>
      <div class="article-page mdui-typo">
        <?php echo parsePicture(parseBiaoQing($this->content)); ?>
        <div class="article-cc">
          如无特殊说明，则本站文章皆为本人码字原创，在文章创作过程中借鉴了其他学者论文、专著及其他文献等，本人皆会在参考文献中注明，如若侵犯您版权，请联系本人删除。特此声明！
        </div>
      </div>
    </div>
    <?php $this->need('includes/comments.php'); ?>
  </div>
</div>
<?php $this->need('includes/footer.php'); ?>