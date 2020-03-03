<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Archive
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 0.0.5(Beta)
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('includes/header.php');
?>
<div class="container">
  <?php
  $this->need('includes/sidebar.php');
  if ($this->have()) :

  ?>
    <div class="left">
      <div class="mdui-card page-card mdui-shadow-10">
        <div class="archive-title">
          <span><?php $this->archiveTitle(array(
                  'category'  =>  _t('分类 <i>%s</i> 下的文章'),
                  'search'    =>  _t('包含关键字 <i>%s</i> 的文章'),
                  'tag'       =>  _t('标签 <i>%s</i> 下的文章'),
                  'author'    =>  _t('<i>%s</i> 发布的文章')
                ), '', '');
                ?></span>
        </div>
      </div>
      <?php
      while ($this->next()) :
        if ($this->fields->articleType == "article" or $this->fields->articleType == NULL) { ?>
          <div class="mdui-card page-card mdui-shadow-10 page">
            <div class="mdui-card-media page-img">
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
    <div class="left">
      <div class="mdui-card page-card mdui-shadow-10">
        <div class="archive-title">
          <p>不要看这里！这里没有文章呐！</p>
        </div>
      </div>
    </div>
  <?php
  endif; ?>
</div>
<?php
$this->need('includes/footer.php'); ?>