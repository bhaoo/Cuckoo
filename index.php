<?php
/**
 * 「Cuckoo」—— 做一只“布咕鸟”
 * 主题文档：<a href="https://cuckoo.owo.show">https://cuckoo.owo.show</a>
 * 项目地址：<a href="https://github.com/bhaoo/cuckoo">https://github.com/bhaoo/cuckoo</a>
 * 感谢 娃子wazi 提供图片授权！
 *
 * @package Cuckoo
 * @author Bhao
 * @version 2.1.0-RC.0
 * @link https://dwd.moe
 * @date 2020-02-02
 */

if(!defined('__TYPECHO_ROOT_DIR__')) exit;
$this -> need('includes/header.php');
$sticky = $this->options->sticky;
if ($sticky && $this->is('index') || $this->is('front')) {
  $sticky_cids = explode(',', strtr($sticky, ' ', ','));
  $sticky_html = "<span class='mdui-text-color-theme' id='sticky'>[置顶] </span>";
  $db = Typecho_Db::get();
  $pageSize = $this->options->pageSize;
  $select1 = $this->select()->where('type = ?', 'post');
  $select2 = $this->select()->where('type = ? && status = ? && created < ?', 'post', 'publish', time());
  $this->row = [];
  $this->stack = [];
  $this->length = 0;
  $order = '';
  foreach ($sticky_cids as $i => $cid) {
    if ($i == 0) $select1->where('cid = ?', $cid);
    else $select1->orWhere('cid = ?', $cid);
    $order .= " when $cid then $i";
    $select2->where('table.contents.cid != ?', $cid);
  }
  if ($order) $select1->order("", "(case cid$order end)");
  if ($this->_currentPage == 1) foreach ($db->fetchAll($select1) as $sticky_post) {
    $sticky_post['sticky'] = $sticky_html;
    $this->push($sticky_post);
  }
  $uid = $this->user->uid;
  if ($uid) $select2->orWhere('authorId = ? && status = ?', $uid, 'private');
  $sticky_posts = $db->fetchAll($select2->order('table.contents.created', Typecho_Db::SORT_DESC)->page($this->_currentPage, $this->parameter->pageSize));
  foreach ($sticky_posts as $sticky_post) $this->push($sticky_post);
  $this->setTotal($this->getTotal() - count($sticky_cids));
}
?>
<div class="index-container">
  <div class="mdui-col-md-8">
    <div class="article">
<?php if ($this->have()) :
  while ($this->next()) :
    if ($this->fields->articleType == "article" or $this->fields->articleType == NULL) { ?>
      <a class="post" href="<?php $this -> permalink() ?>">
        <div class="mdui-card index-card mdui-hoverable">
          <div class="mdui-card-media index-img-media">
            <div class="index-img" data-bg="<?php $wzimg = $this->fields->wzimg;if (!empty($wzimg)) {echo $wzimg;} else {echo randPic();} ?>"></div>
            <div class="index-card-filter"></div>
            <div class="mdui-card-media-covered">
              <div class="mdui-card-primary index-primary">
                <div class="mdui-card-primary-title"><?php $this->sticky();$this -> title(); ?></div>
                <div class="mdui-card-primary-subtitle index-info"><?php $this -> date(); ?>
                  <?php if ($this->options->showComments) {
                    echo '｜'; $this -> commentsNum('0 条评论', '1 条评论', '%d 条评论');
                  } ?>
                </div>
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
    <?php } elseif ($this->fields->articleType == "normal") { ?>
      <a class="post index-normal" href="<?php $this -> permalink() ?>">
        <div class="mdui-card index-card-normal mdui-hoverable">
          <div class="mdui-card-primary index-primary-normal">
            <div class="mdui-card-primary-title"><?php $this->sticky();$this->title(); ?></div>
            <div class="mdui-card-primary-subtitle"><?php $this->date(); ?>
              <?php if ($this->options->showComments) {
                echo '｜'; $this -> commentsNum('0 条评论', '1 条评论', '%d 条评论');
              } ?>
            </div>
            <?php if (strlen($this->fields->remark)) {
              echo '<div class="mdui-chip mdui-color-theme index-chip-normal"><span class="mdui-chip-title">'.$this->fields->remark.'</span></div>';
            } ?>
          </div>
        </div>
      </a>
      <?php
    }
    endwhile; ?>
    </div>
    <?php $this->pageLink('下一页', 'next'); ?>
    <div class="changePage changePage-end">
      <span class="infinite-scroll-request">加载中...</span>
      <span class="infinite-scroll-last">到底了啦</span>
    </div>
    <?php if($this->getTotal() > 1 && $this->getTotalPage() !== 1){ ?>
      <div class="changePage changePage-load">加载更多</div>
    <?php }else{ ?>
      <div class="changePage">到底了啦</div>
    <?php } ?>
    <?php else : ?>
  <div class="mdui-card page-card mdui-shadow-10">
    <div class="archive-title">
      <p>暂无文章</p>
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