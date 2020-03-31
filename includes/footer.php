<?php 
/**
 * .______    __    __       ___        ______
 * |   _  \  |  |  |  |     /   \      /  __  \
 * |  |_)  | |  |__|  |    /  ^  \    |  |  |  |
 * |   _  <  |   __   |   /  /_\  \   |  |  |  |
 * |  |_)  | |  |  |  |  /  _____  \  |  `--'  |
 * |______/  |__|  |__| /__/     \__\  \______/
 * 
 * Footer
 * 
 * @author Bhao
 * @link https://dwd.moe/
 * @version 1.0.3
 */
?>
<div onclick="tocOverlay()" class="toc-overlay"></div>
<button class="mdui-fab mdui-fab-fixed mdui-ripple top mdui-color-theme-accent"><i class="mdui-icon material-icons">arrow_upward</i></button>
<footer class="footer">
  <center><?php Footer(); ?></center>
</footer>
<?php //保留版权就是对作者最大的支持，若删除/修改版权则视为侵权，将停止主题相关服务
$this->footer(); statisticsBaidu();?>
<script src="<?php staticFiles('assets/js/mdui.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.pjax.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.ias.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.lazyload.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/highlight.min.js') ?>"></script>
<script> $(document).on('pjax:complete',function(){<?php otherPjax(); ?>})</script>
<script src="<?php staticFiles('assets/js/cuckoo.min.js') ?>"></script>
</body>
</html>