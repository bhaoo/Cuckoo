<?php
/**
 * Links
 *
 * @package custom
 * @author Bhao
 * @link https://dwd.moe/
 * @version 2.0.0
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('includes/header.php'); ?>
<div class="index-container">
  <div class=<?php echo postContainerClassName() . "page-content"; ?>>
    <?php Links() ?>
    <div class="mdui-col-sm-12">
      <div class="mdui-card post-content mdui-typo">
          <?php parseContent($this->content); ?>
      </div>
      <?php $this->need('includes/comments.php'); ?>
    </div>
  </div>
  <?php $this -> need('includes/sidebar.php'); ?>
</div>
<?php $this -> need('includes/footer.php'); ?>
