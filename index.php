<?php

/**
 * 「Cuckoo」—— 做一只“布咕鸟”
 * 作者：Bhao
 *
 * @package Cuckoo
 * @author Bhao
 * @version 0.0.2(Beta)
 * @link https://dwd.moe
 * @date 2020-02-02
 */

$this->need('includes/header.php');
?>
<div class="container">
  <?php $this->need('includes/sidebar.php'); ?>
  <div class="left">
    <?php if ($this->have()) :
      while ($this->next()) :
        if ($this->fields->articleType == "article") { ?>
          <div class="mdui-card page-card mdui-shadow-10 page">
            <div class="mdui-card-media">
              <div class="page-img" data-original="<?php $wzimg = $this->fields->wzimg;
                                                    if (!empty($wzimg)) {
                                                      echo $wzimg;
                                                    } else {
                                                      echo randPic();
                                                    } ?>"></div>
            </div>
            <div class="mdui-card-primary page-primary">
              <div class="mdui-card-primary-title"><a href="<?php $this->permalink() ?>"><?php $this->sticky();
                                                                                          $this->title() ?></a></div>
              <div class="mdui-card-primary-subtitle"><?php $this->date(); ?>｜<?php $this->commentsNum('0 条评论', '1 条评论', '%d 条评论'); ?></div>
            </div>
            <div class="mdui-card-content page-content"><?php $this->excerpt(70, ' ...'); ?></div>
            <div class="mdui-card-actions">
              <a href="<?php $this->permalink() ?>"><button class="mdui-btn mdui-float-right">
                  <font color="#E91E63">点击查看</font>
                </button></a>
            </div>
          </div>
        <?php
        } elseif ($this->fields->articleType == "daily") { ?>
          <div class="mdui-card page-card mdui-shadow-10">
            <div class="mdui-card-primary">
              <div class="daily-icon"><i class="mdui-icon material-icons">insert_comment</i></div>
              <div class="mdui-card-primary-title daily-title"><a href="<?php $this->permalink() ?>"><?php $this->title(); ?></a></div>
              <div class="mdui-card-primary-subtitle daily-subtitle"><?php $this->date(); ?>｜<?php $this->commentsNum('0 条评论', '1 条评论', '%d 条评论'); ?></div>
            </div>
            <div class="mdui-card-actions daily-button">
              <a href="<?php $this->permalink() ?>"><button class="mdui-btn mdui-float-right">
                  <font color="#E91E63">点击查看</font>
                </button></a>
            </div>
          </div>
      <?php
        }
      endwhile;
      ?>
      <div class="changePage">
        <?php $this->pageLink('下一页', 'next'); ?>
      </div>
  </div>
<?php
    else : ?>
  暂无文章
<?php
    endif; ?>
</div>
<?php $this->need('includes/footer.php'); ?>