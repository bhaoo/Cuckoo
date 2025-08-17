<?php
/**
 * Gallery
 * 
 *
 * @Creator perriya
 * @date 2025-8-15
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<?php

//Gallery生成
//会忽略除img以外的元素

function parseGallery($content) {

    $callback = function($m){
        $result = "";

        $img = $m[0];

        $patt_img = '/<img(.*?)src="(.*?)"(.*?)>/s';
        $text_image = '<img $1 src="$2" $3 onclick="window.open(\'$2\')" loading="lazy">';
        $img = preg_replace($patt_img, $text_image, $img);

        $result = $result . '<div class="gallery-item">';
        $result = $result . $img;
        $result = $result . '</div>';

        echo $result;
    };

    $img_pattern = '/<img\b[^>]*>/i';

    preg_replace_callback($img_pattern, $callback, $content);
};

?>

<div class="gallery-container" id="gallery-container">
    <div class="gallery-sizer"></div>
    <?php parseGallery($this->content); ?>
</div>

<script>

var grid = document.querySelector('.gallery-container');
var msnry = new Masonry(grid,{
  itemSelector: '.gallery-item',
  columnWidth: '.gallery-sizer',
  gutter: 0,
  percentPosition: true
});
imagesLoaded(grid).on('progress',function(){
  msnry.layout();
  
  //footer定位
  var document_height = innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
  var gallery_container_height = document.getElementById('gallery-container').offsetHeight;
  var total_height = gallery_container_height + 150;
  
  if (document_height < total_height) {
    document.getElementById('footer').classList.remove('gallery-footer');
  }
  
});
</script>


<?php $this -> need('includes/footer.php'); ?>