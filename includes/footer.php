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
 * @version 0.0.2(Beta)
 */
?>
<button class="mdui-fab mdui-fab-fixed mdui-ripple top"><i class="mdui-icon material-icons">arrow_upward</i></button>
<footer class="footer">
  <center>
    <p>
      &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a><br><br>
      Theme <a href="">Cuckoo</a> by <a href="https://dwd.moe/">Bhao</a>｜Powered By <a href="http://www.typecho.org">Typecho</a>
    </p>
  </center>
</footer>
<?php //保留版权就是对作者最大的支持，若删除/修改版权则视为侵权，将停止主题相关服务
$this->footer(); ?>
<script src="<?php staticFiles('assets/js/mdui.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.pjax.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.ias.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/jquery.lazyload.min.js') ?>"></script>
<script src="<?php staticFiles('assets/js/cuckoo.js') ?>"></script>
</body>
</html>