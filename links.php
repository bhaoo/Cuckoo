<?php
/**
 * Links
 *
 * @package custom
 * @author Bhao
 * @link https://dwd.moe/
 * @date 2023-12-04
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('includes/header.php'); ?>
<div class="index-container">
  <div class="mdui-col-md-8 page-content">
    <?php Links() ?>
    <div class="mdui-col-sm-12">
      <?php if ($this->content) { ?>
      <div class="mdui-card post-content mdui-typo">
          <?php parseContent($this->content); ?>
      </div>
      <?php }
      $this->need('includes/comments.php'); ?>
    </div>
  </div>
  <?php $this -> need('includes/sidebar.php'); ?>
</div>
<?php $this -> need('includes/footer.php'); ?>
