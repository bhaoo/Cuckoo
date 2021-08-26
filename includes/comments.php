<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * Functions
 *
 * @author Bhao
 * @link https://dwd.moe/
 * @version 2.0.0
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

//$this->comments()->to($comments);
require_once __DIR__ . "/../libs/comments.php";
$parameter = array(
  'parentId'      => $this->hidden ? 0 : $this->cid,
  'parentContent' => $this->row,
  'respondId'     => $this->respondId,
  'commentPage'   => $this->request->filter('int')->commentPage,
  'allowComment'  => $this->allow('comment')
);
$this->widget('Cuckoo_Comments_Archive', $parameter)->to($comments);

if ($this->allow('comment')) : ?>
  <div class="mdui-dialog emoji-dialog" id="emoji">
    <div class="emoji-box">
      <div class="emoji-top mdui-dialog-title">
        Emoji
        <div class="emoji-cancel">
          <button class='mdui-btn mdui-btn-icon' mdui-dialog-close no-pjax><i class='mdui-icon material-icons'>cancel</i></button>
        </div>
      </div>
      <div class="mdui-divider"></div>
      <div class="mdui-dialog-body mdui-dialog-content"><?php Smile::getOwO(); ?></div>
      <div class="mdui-tab mdui-tab-full-width" mdui-tab>
        <?php Smile::getTitle(); ?>
      </div>
    </div>
  </div>

  <div class="comment-header">
    <div class="comment-button">
      <button id="sendComment-title-owo-button" class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#emoji',history: false}"><i class='mdui-icon material-icons'>insert_emoticon</i></button>
    </div>
    <div class="comment-title">发表评论</div>
  </div>
  <div id="<?php $this->respondId(); ?>" class="mdui-card comment-respond" data-commentUrl="<?php $this->commentUrl() ?>">
    <div class="mdui-card-content">
      <form id="comment-form" onKeyDown="if(event.ctrlKey && event.keyCode == 13){Comments.submit();return false;}">
        <textarea placeholder="大佬呐！看这里！这里留言鸭！" id="comment-textarea" class="comment-textarea" name="text" rows="8" cols="50" tabindex="4" required><?php $this->remember('text'); ?></textarea>
        <?php if ($this->user->hasLogin()) : ?>
          <div class="mdui-typo comment-text">
            <div class="comment-login">
              登录身份：<a no-pjax href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
              <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a>
            </div>
            <button id="sendComment-input-owo-button" type="button" class="mdui-btn mdui-btn-icon" mdui-dialog="{target: '#emoji', history: false}" style="display: none;"><i class='mdui-icon material-icons'>insert_emoticon</i></button>
            <?php $comments->cancelReply('<button id="sendComment-title-reply-button" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">cancel</i></button>'); ?>
          </div>
        <?php else : ?>
        <div>
          <div class="mdui-textfield mdui-col-md-6">
            <i class="mdui-icon material-icons">account_circle</i>
            <label class="mdui-textfield-label">昵称</label>
            <input class="mdui-textfield-input comment-input-author" type="text" name="author" value="<?php $this->remember('author'); ?>" required></input>
          </div>
          <div class="mdui-textfield mdui-col-md-6">
            <i class="mdui-icon material-icons">email</i>
            <label class="mdui-textfield-label" <?php if ($this->options->commentsRequireMail) : ?> class="required" <?php endif; ?>>邮箱</label>
            <input class="mdui-textfield-input comment-input-email" id="email" type="email" name="mail" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail) : ?> required<?php endif; ?>></input>
          </div>
          <div>
            <div class="mdui-textfield comment-web">
              <i class="mdui-icon material-icons">web</i>
              <label class="mdui-textfield-label" <?php if ($this->options->commentsRequireURL) : ?> class="required" <?php endif; ?>>网址(选填)</label>
              <input class="mdui-textfield-input" type="url" name="url" placeholder="<?php _e('http://'); ?>" value="<?php $this->remember('url'); ?>" <?php if ($this->options->commentsRequireURL) : ?> required<?php endif; ?>></input>
            </div>
          </div>
        </div>
        <?php endif; ?>
        <div class="comment-form-button">
          <button id="sendComment-input-owo-button" type="button" class="mdui-btn mdui-btn-icon emoji" mdui-dialog="{target: '#emoji', history: false}" style="display: none;"><i class='mdui-icon material-icons'>insert_emoticon</i></button>
          <?php $comments->cancelReply('<button id="sendComment-title-reply-button" class="mdui-btn mdui-btn-icon"><i class="mdui-icon material-icons">cancel</i></button>'); ?>
          <button type="button" onclick="Comments.submit()" id="submit" class="mdui-btn mdui-text-color-theme mdui-ripple submit">发表评论</button>
        </div>
      </form>
    </div>
  </div>
  <div class="comment-title">全部评论</div>
  <div class="comments-container">
    <?php if ($comments->have()) {
      $comments->listComments();
    } else { ?>
      <div class="mdui-card post-card">
        <div class="mdui-card-content">
          <div class="comment-info">
            <p><i class="mdui-icon material-icons">info</i> 还没有任何评论，你来说两句呐!</p>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
<?php else : ?>
  <div class="mdui-card post-card">
    <div class="mdui-card-content">
      <div class="comment-info">
        <p><i class="mdui-icon material-icons">info</i> 评论功能已经关闭了呐!</p>
      </div>
    </div>
  </div>
<?php endif; ?>
<?php if ($this->options->commentsAntiSpam) { Cuckoo_Comments_Archive::AntiSpam($this); } ?>