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
 * @version 2.0.0
 */

if(!defined('__TYPECHO_ROOT_DIR__')) exit;
$this -> need('includes/header.php');
?>
  <div class="index-container">
    <div class=<?php echo postContainerClassName(); ?>>
      <?php if ($this->have()) : ?>
      <div class="mdui-card archive-card mdui-hoverable">
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
      <div class="article">
        <?php while ($this->next()) :
          if ($this->fields->articleType == "article" or $this->fields->articleType == NULL) { ?>
            <a class="post" href="<?php $this -> permalink() ?>">
              <div class="mdui-card index-card mdui-hoverable">
                <div class="mdui-card-media index-img-media">
                  <div class="index-img" data-bg="<?php $wzimg = $this->fields->wzimg;if (!empty($wzimg)) {echo $wzimg;} else {echo randPic();} ?>"></div>
                  <div class="index-card-filter"></div>
                  <div class="mdui-card-media-covered">
                    <div class="mdui-card-primary index-primary">
                      <div class="mdui-card-primary-title"><?php $this -> title(); ?></div>
                      <div class="mdui-card-primary-subtitle index-info"><?php $this -> date(); ?>
                        ｜<?php $this -> commentsNum('0 条评论', '1 条评论', '%d 条评论'); ?></div>
                      <div class="mdui-card-primary-subtitle index-subtitle"><?php $this -> excerpt(); ?></div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          <?php } elseif ($this->fields->articleType == "daily") { ?>
            <div class="mdui-card index-card-daily mdui-hoverable post">
              <div class="index-icon mdui-shadow-3">
                <div></div>
                <i class="mdui-icon material-icons">message</i>
              </div>
              <div class="mdui-card-primary">
                <div class="mdui-card-primary-subtitle"><?php $this->sticky(); $this -> date('Y-m-d H:i:s'); ?></div>
              </div>
              <div class="mdui-card-content mdui-typo"><?php parseContent(parseBiaoQing($this->content)); ?></div>
            </div>
            <?php
          }
        endwhile; ?>
      </div>
      <?php $this->pageLink('下一页', 'next'); ?>
      <div class="changePage changePage-end">
        <span class="infinite-scroll-request">加载中...</span>
        <span class="infinite-scroll-last">到底了啦</span>
      </div>
      <?php if($this->getTotal() > 1){ ?>
          <div class="changePage changePage-load">加载更多</div>
      <?php }else{ ?>
          <div class="changePage">到底了啦</div>
      <?php } ?>
      <?php else : ?>
        <div class="mdui-card archive-card mdui-hoverable">
          <div class="archive-title">
            <p>不要看这里！这里没有文章呐！</p>
          </div>
        </div>
      <?php endif;
      if ($this->_currentPage < ceil($this->getTotal() / $this->parameter->pageSize)) { ?>
        <div class="checkLast"></div>
      <?php } ?>
    </div>
    <?php $this -> need('includes/sidebar.php'); ?>
  </div>
<?php $this -> need('includes/footer.php'); ?>