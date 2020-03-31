<?php
/**
 * Links
 * 
 * @package custom
 * @author Bhao
 * @link https://dwd.moe/
 * @version 1.0.3
 */

$this->comments()->to($comments);
function threadedComments($comments, $options) {
  $commentClass = '';
  if ($comments->authorId) {
      if ($comments->authorId == $comments->ownerId) {
          $commentClass .= ' comment-by-author';  //如果是文章作者的评论添加 .comment-by-author 样式
      } else {
          $commentClass .= ' comment-by-user';  //如果是评论作者的添加 .comment-by-user 样式
      }
  }
  $commentLevelClass = $comments->_levels > 0 ? ' comment-child' : ' comment-parent';  //评论层数大于0为子级，否则是父级
?>
<div id="<?php $comments->theId(); ?>"class="comments-list
  <?php
  if($comments->levels > 0){
    echo ' comment-child';
    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
  } else {
    echo ' comment-parent';
  }
  $comments->alt(' comment-odd', ' comment-even');
  echo $commentClass;
  ?>"
>
  <div class="comment-header">
    <div class="comment-header-image">
      <img class="mdui-img-circle" src="<?php get_comment_avatar($comments->mail); ?>"/>
    </div>
    <?php get_comment_prefix($comments->mail); if($comments->authorId == $comments->ownerId){ ?>
      <img src="<?php staticFiles('assets/images/grade/author.png') ?>" class="comment-prefix" mdui-tooltip="{content: '博主'}"/>
    <?php } ?>
  </div>
  <div class="mdui-card-header-title mdui-typo comment-author"><?php $comments->author(); ?><?php getBrowser($comments->agent); getOs($comments->agent); ?></div>
  <div class="mdui-card-header-subtitle"><?php $comments->date('Y-m-d H:i'); ?></div>
  <div class="mdui-card-menu">
    <?php $comments->reply('<button class="mdui-btn mdui-btn-dense mdui-ripple comment-reply">回复</button>'); ?>
  </div>
  <div class="comment-content mdui-typo">
    <?php echo preg_replace('#</?[p][^>]*>#','', parseBiaoQing($comments->content)); ?>
  </div>
  <?php if ($comments->children) { ?>
  <div class="comment-children">
      <?php $comments->threadedComments($options); ?>
  </div>
  <?php } ?>
</div>
<?php
}
$this->need('includes/header.php');
?>
<div class="container">
<?php
$this->need('includes/sidebar.php');
?>
  <div class="left">
    <div class="links-content">
      <?php if(array_key_exists("Links", Typecho_Plugin::export()['activated'])){
      Links_Plugin::output("
      <a class='links-url' href='{url}'>
        <div class='mdui-col-sm-6'>
          <div class='links-card mdui-shadow-10'>
            <div class='links-img'><img class='mdui-img-circle' src='{image}'/></div>
            <div class='links-name'>{name}</div>
            <div class='links-describe'>{description}</div>
          </div>
        </div>
      </a>");}
    echo '</div>';
    if(!$this->content == ""){echo '<div class="mdui-card page-card mdui-shadow-10 mdui-typo links-comments">'.parseContent(parseBiaoQing($this->content)).'</div>';}
    if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="mdui-card page-card mdui-shadow-10 links-comments comment-id">
      <div class="comment-cancel">
      <button class='mdui-btn mdui-btn-icon' mdui-dialog="{target: '#emoji'}"><i class='mdui-icon material-icons'>insert_emoticon</i></button>
        <?php $comments->cancelReply("<button class='mdui-btn mdui-btn-icon'><i class='mdui-icon material-icons'>cancel</i></button>"); ?>
      </div>
      <h3>交换友链</h3>
      <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
        <textarea placeholder="大佬呐！看这里！在这里交换鸭！" id="comment-textarea" name="text" class="comment-textarea" rows="8" cols="50" tabindex="4" required ><?php $this->remember('text'); ?></textarea>
        <?php if($this->user->hasLogin()): ?>
        <div class="mdui-typo">
          <p>
            登录身份：<a no-pjax href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
            <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a>
          </p>
        </div>
        <?php else: ?>
        <div class="mdui-textfield comment-left">
          <img src="<?php staticFiles('assets/images/avatar.png') ?>" class="mdui-icon mdui-img-circle comments-avatar"/>
          <label class="mdui-textfield-label">昵称</label>
          <input class="mdui-textfield-input" type="text" name="author" value="<?php $this->remember('author'); ?>" required></input>
        </div>
        <div class="mdui-textfield comment-right">
          <i class="mdui-icon material-icons">email</i>
          <label class="mdui-textfield-label" <?php if ($this->options->commentsRequireMail): ?> class="required"<?php endif; ?>>邮箱</label>
          <input class="mdui-textfield-input" id="email" type="email" name="mail" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>></input>
        </div>
        <div class="mdui-textfield comment-middle">
          <i class="mdui-icon material-icons">web</i>
          <label class="mdui-textfield-label" <?php if ($this->options->commentsRequireURL): ?> class="required"<?php endif; ?>>网址(选填)</label>
          <input class="mdui-textfield-input" type="url" name="url" placeholder="<?php _e('http://'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?>></input>
        </div>
        <?php endif; ?>
        <center>
          <button type="submit" id="submit" class="mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple submit">发表评论</button>
        </center>
      </form>
    </div>
    <div id="comment-list" class="mdui-card page-card mdui-shadow-10 links-comment-list">
      <div class="mdui-card-content">
        <div class="comment-count">
          <h3>全部留言 <?php $this->commentsNum(_t('(暂无留言)'), _t('(共 1 条留言)'), _t('(共 %d 条留言)')); ?></h3>
        </div>
        <?php if ($comments->have()): ?>
        <div class="comment-container">
          <?php $comments->listComments(); ?>
        </div>
        <div class="comment-line"></div>
        <?php else: ?>
        <div class="comment-line"></div>
        <div class="comment-info">
          <p><i class="mdui-icon material-icons">info</i> 还没有任何留言，快来和咱交换友链伐!</p>
        </div>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <div id="comment-list" class="mdui-card page-card mdui-shadow-10">
      <div class="mdui-card-content single-comments-content">
        <h3>全部留言</h3>
        <div class="comment-line"></div>
        <div class="comment-info">
          <p><i class="mdui-icon material-icons">info</i> 交换友链已经关闭了呐!</p>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div><div class="mdui-dialog" id="emoji">
  <div class="emoji-box">
    <div class="emoji-top mdui-dialog-title">
      Emoji
      <div class="emoji-cancel">
        <button class='mdui-btn mdui-btn-icon' mdui-dialog-close><i class='mdui-icon material-icons'>cancel</i></button>
      </div>
    </div>
    <div class="mdui-divider"></div>
    <div class="mdui-dialog-body mdui-dialog-content"><?php Smile::getOwO(); ?></div>
    <div class="mdui-tab mdui-tab-full-width tab" mdui-tab>
    <?php Smile::getTitle(); ?>
    </div>
  </div>
</div></div>
</div>
<?php $this->need('includes/footer.php'); ?>