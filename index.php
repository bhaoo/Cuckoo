<?php

/**
 * 「Cuckoo」—— 做一只“布咕鸟”
 * 作者：<a href="https://dwd.moe">Bhao</a>
 * 主题文档：<a href="https://cuckoo.owo.show">https://cuckoo.owo.show</a>
 * 项目地址：<a href="https://github.com/bhaoo/cuckoo">https://github.com/bhaoo/cuckoo</a>
 *
 * @package Cuckoo
 * @author Bhao
 * @version 1.0.3
 * @link https://dwd.moe
 * @date 2020-02-02
 */

$this->need('includes/header.php');
$sticky = $this->options->sticky;
if ($sticky && $this->is('index') || $this->is('front')) {
  $sticky_cids = explode(',', strtr($sticky, ' ', ','));
  $sticky_html = "<span id='sticky'>[置顶] </span>";
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
  if ($order) $select1->order(null, "(case cid$order end)");
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
<div class="container">
  <?php $this->need('includes/sidebar.php'); ?>
  <div class="left">
    <?php if ($this->have()) :
      while ($this->next()) :
        if ($this->fields->articleType == "article" or $this->fields->articleType == NULL) { ?>
          <div class="mdui-card page-card mdui-shadow-10 page">
            <div class="mdui-card-media">
              <div class="page-img" data-original="<?php $wzimg = $this->fields->wzimg;if (!empty($wzimg)) { echo $wzimg;} else {echo randPic();} ?>"></div>
            </div>
            <div class="mdui-card-primary page-primary">
              <div class="mdui-card-primary-title"><a href="<?php $this->permalink() ?>"><?php $this->sticky();$this->title(); ?></a></div>
              <div class="mdui-card-primary-subtitle"><?php $this->date(); ?>｜<?php $this->commentsNum('0 条评论', '1 条评论', '%d 条评论'); ?></div>
            </div>
            <div class="mdui-card-content page-content"><?php $this->excerpt(70, ' ...'); ?></div>
            <div class="mdui-card-actions">
              <a href="<?php $this->permalink() ?>"><button class="mdui-btn mdui-float-right mdui-text-color-theme">点击查看</button></a>
            </div>
          </div>
        <?php
        } elseif ($this->fields->articleType == "daily") { ?>
          <div class="mdui-card page-card mdui-shadow-10 page">
            <div class="mdui-card-primary">
              <div class="daily-icon"><i class="mdui-icon material-icons">insert_comment</i></div>
              <div class="mdui-card-primary-title daily-title"><a href="<?php $this->permalink() ?>"><?php $this->sticky();$this->title(); ?></a></div>
              <div class="mdui-card-primary-subtitle daily-subtitle"><?php $this->date(); ?>｜<?php $this->commentsNum('0 条评论', '1 条评论', '%d 条评论'); ?></div>
            </div>
            <div class="mdui-card-actions daily-button">
              <a href="<?php $this->permalink() ?>"><button class="mdui-btn mdui-float-right mdui-text-color-theme">点击查看</button></a>
            </div>
          </div>
      <?php
        }
      endwhile;
      ?>
      <div class="changePage">
        <?php $this->pageLink('下一页', 'next'); ?>
      </div>
<?php else : ?>
    <div class="mdui-card page-card mdui-shadow-10">
      <div class="archive-title">
        <p>暂无文章</p>
      </div>
    </div>
<?php endif; ?>
  </div>
</div>
<?php $this->need('includes/footer.php'); ?>