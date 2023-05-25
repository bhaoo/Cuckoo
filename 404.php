<?php
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 *
 * 404
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
        <div class="mdui-card archive-card mdui-hoverable">
          <div class="archive-title">
            <p>不要看这里！这里没有文章呐！</p>
          </div>
        </div>
    </div>
    <?php $this -> need('includes/sidebar.php'); ?>
  </div>
<?php $this -> need('includes/footer.php'); ?>